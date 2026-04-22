# Image Handling

Two parts:

1. **Current state** — what's already implemented and deployed (as of commit `be488ba`).
2. **Planned: keep originals** — design to build next, preserves untouched masters alongside the web-optimized copy.

---

## Current state (shipped)

### Problem

Prod (Hostpoint shared hosting) crashed when Glide tried to resize multi-megapixel originals on-demand. A 7768×5179 JPEG needs ~150 MB RAM just to decode, before any processing. Concurrent requests compounded the issue.

### Solution

Cap stored originals at a sane size, and pre-generate all Glide variants in a queued job after upload. Prod only ever serves cached files — no runtime resizing.

### Lifecycle

**1. Upload (`UploadAction`)**
- File stored to `storage/app/public/temp/{file}`.
- If image and long edge > 3000px: `ImageDownsizer` resizes in place via Intervention/Imagick, re-encodes at quality 90, overwrites.
- Returns updated metadata (width, height, size) to the Vue dashboard.

**2. Persist (`PersistAction`)**
- Moves `temp/{file}` → `uploads/{file}` on the public disk.
- Creates the `Media` DB row.
- Dispatches `WarmGlideCacheJob::dispatch($media->uuid)` onto the `database` queue.

**3. Warm job (`WarmGlideCacheJob`)**
- Bumps PHP `memory_limit` to 512 MB.
- Iterates: widths (`[480, 640, 768, 1024, 1280, 1440, 1600, 1920]` capped at media width) × formats (`avif`, `webp`, `jpg`) × fits (`crop`, `max`).
- Calls `$server->makeImage()` for each combination. Populates `storage/app/.glide-cache/`.
- Typical image ≈ 48 variants per warm pass, single in-memory decode per format.
- `tries = 2`, `timeout = 300`. Failures land in `failed_jobs`.

**4. Frontend request**
- `<x-media.image :media="$media">` renders `<picture>` with signed URLs.
- Browser hits `/img/uploads/{file}?w=...&fm=...&s={hash}` → `ImageController::show`.
- Because the variant is already cached, this is a pure file read — no Imagick, no decode.
- Response: `Cache-Control: public, max-age=31536000, immutable`.

### Config (`config/media.php`)

```php
'max_upload_edge' => 3000,      // downsize cap on upload
'upload_quality'  => 90,        // JPEG quality for re-encoded originals
'widths'          => [480, 640, 768, 1024, 1280, 1440, 1600, 1920],
'formats'         => ['avif', 'webp', 'jpg'],
'fits'            => ['crop', 'max'],
'quality'         => 90,        // Glide variant quality
'warm_memory_limit' => '512M',
```

### Scheduler

`routes/console.php` runs `queue:work --stop-when-empty --tries=3 --timeout=300` every minute, wrapped in `withoutOverlapping()` and `runInBackground()`. Driven by the existing `schedule:run` cron entry — no second cron needed.

### Backfill command

`php artisan media:reprocess`

- Iterates all `Media` rows with `mime_type LIKE 'image/%'`.
- Downsizes originals > 3000px in place, updates DB width/height/size.
- Dispatches warm job for every image.
- Flags: `--no-warm` (skip cache warming), `--no-resize` (only warm, don't touch files).

### Files

- `config/media.php`
- `app/Support/ImageDownsizer.php`
- `app/Actions/Media/UploadAction.php`
- `app/Actions/Media/PersistAction.php`
- `app/Jobs/WarmGlideCacheJob.php`
- `app/Console/Commands/ReprocessMedia.php`
- `app/View/Components/Media/Image.php` (reads from `config/media.php`)
- `routes/console.php` (queue worker schedule)

---

## Planned: keep originals

### Motivation

Preserve the untouched full-res master alongside the 3000px web version for:

- Print production
- Client re-requests at higher resolution
- Archival

The web copy stays the thing Glide reads and serves. The original is private, never publicly accessible, only downloadable by authenticated dashboard users.

### Storage layout

- `storage/app/public/uploads/{file}` — 3000px web version (Glide reads this). **Unchanged.**
- `storage/app/originals/{file}` — untouched master, same filename. **Private disk, not symlinked into public/.**

### Filesystem config

Add to `config/filesystems.php`:

```php
'originals' => [
    'driver' => 'local',
    'root'   => storage_path('app/originals'),
    'throw'  => false,
],
```

### DB

**No new column.** Convention: if a file exists at the same path on the `originals` disk, there's a master for that media row. Keeps it simple, no migration.

### Upload flow changes

**`UploadAction`**
- After storing to `temp/{file}` on the public disk, *before* calling the downsizer, copy the file to `temp-originals/{file}` on the `originals` disk.
- Then downsize `temp/{file}` as today.
- Skip for non-image uploads (PDFs).

**`PersistAction`**
- Move `temp/{file}` → `uploads/{file}` on public disk (as today).
- Move `temp-originals/{file}` → `{file}` on originals disk (new).
- Skip the second move if the temp original doesn't exist (non-image, or legacy).

**`DeleteAction`**
- On hard delete, remove from originals disk too.

**`CleanupMedia` command (existing, runs daily)**
- Also clean `temp-originals/` older than 24h.

### Download endpoint

New route:

```
GET /api/dashboard/media/{media}/original
```

Controller method streams the file from the `originals` disk with `Content-Disposition: attachment; filename="{original_name}"`.

- **Filename on download:** use `Media::original_name` (e.g. `burenweg-2022.jpg`), not the stored unique filename.
- **404** if no master exists (pre-backfill media, PDFs, etc.).
- **Auth:** behind `['web', 'auth']` middleware.
- **Policy:** restrict to admin only? Or admin + editor? *(decision needed — see Open questions)*

### Dashboard UI

- `MediaResource` exposes `has_original` boolean (checks `Storage::disk('originals')->exists($media->file)`).
- Vue media component shows a "Download original" action when `has_original` is true and user has permission.
- Action hits the download endpoint, browser handles the file save.

### Backfill — ordering matters

**Critical:** the local `storage/app/public/uploads/` directory right now contains the *actual* masters (28 MB files rsynced from prod). Running `media:reprocess` overwrites them with the 3000px version. So originals must be captured *before* any reprocess pass.

New command: `php artisan media:archive-originals`

- Iterates every image `Media` row.
- For each, if `originals/{file}` does not yet exist, **copy** (not move) `uploads/{file}` → `originals/{file}`.
- Idempotent, safe to re-run, safe to interrupt.

**Updated deploy runbook**

Locally (or on a staging box with the rsynced originals):

1. `php artisan media:archive-originals` — capture current uploads as masters
2. `php artisan media:reprocess` — downsize uploads/, queue warm jobs
3. Queue worker processes warm jobs via scheduler (already wired)
4. rsync `storage/app/public/uploads/` and `storage/app/originals/` up to prod

Originals on prod are accessed only via the authenticated download endpoint.

### Scope of edits

- `config/filesystems.php` — add `originals` disk
- `app/Actions/Media/UploadAction.php` — copy to originals temp before downsize
- `app/Actions/Media/PersistAction.php` — move original on persist
- `app/Actions/Media/DeleteAction.php` — delete from originals disk
- `app/Console/Commands/CleanupMedia.php` — clean `temp-originals/` too
- `app/Console/Commands/ArchiveOriginals.php` — new backfill command
- `app/Http/Controllers/Api/MediaController.php` (or wherever media routes live) — `downloadOriginal` method
- `routes/api.php` (or dashboard route file) — new route
- `app/Http/Resources/MediaResource.php` — expose `has_original`
- `app/Policies/MediaPolicy.php` — possibly new `downloadOriginal` ability
- Vue media component — "Download original" button
- `.gitignore` — ensure `storage/app/originals/` ignored (should be covered by existing `storage/app/*` rules, verify)

### Risks / things to watch

- **Originals disk must not be publicly accessible.** Living under `storage/app/originals/` (not `storage/app/public/`) guarantees this — `public/storage` symlink only points to the `public/` subdir.
- **Disk growth:** ~2 GB for current 1293 images; ~50–200 MB per year of new uploads. Hostpoint disk quota to verify.
- **Backups:** backup scope now includes multi-megapixel masters. Confirm backup job still fits in any time/size budgets.
- **PDFs are not archived.** They're stored as-is; the web copy *is* the original. Download endpoint serves the public uploads copy for PDFs, or is skipped entirely for non-images (decide at implementation time).

### Open questions (decide before implementing)

1. **Download access level:** admin-only, or admin + editor? (Viewers shouldn't have access to masters in any case.)
2. **Download filename:** confirmed `original_name` (e.g. `burenweg-2022.jpg`) — agreed.
3. **Non-image media:** skip the originals flow entirely for PDFs (web copy = original, no separate archive). Agreed default unless you say otherwise.
