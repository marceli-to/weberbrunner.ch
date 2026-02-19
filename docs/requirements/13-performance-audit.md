# Performance Audit

## Overview

Full performance audit for the weberbrunner.ch application. Covers backend (Laravel/PHP), frontend (Vue/Blade), database (MySQL), and infrastructure.

**Audit date:** 2026-02-12

---

## 1. Database Performance

### Query Analysis

- [x] Enable query logging and review all page loads for N+1 queries
- [x] Check for missing indexes on frequently queried columns
- [x] Review eager loading: ensure all API resource endpoints use `->with()`
- [x] Audit polymorphic queries for proper composite indexes
- [x] Verify all foreign key columns have indexes
- [x] Review soft delete queries: ensure `deleted_at` is indexed

### Findings

#### CRITICAL: Missing composite indexes on polymorphic columns

The `media` table uses `morphs('mediable')` which creates `mediable_type` and `mediable_id` columns but **no composite index**. Every polymorphic query (loading media for projects, team members, network entries) does a full table scan.

Similarly, the `activity_log` table is missing composite indexes on:
- `subject_type` + `subject_id`
- `causer_type` + `causer_id`

#### HIGH: Missing indexes on `deleted_at` columns

All 11 soft-deletable tables lack an index on `deleted_at`. Since every query on these models adds `WHERE deleted_at IS NULL`, this forces a full table scan on every request:

- `users`, `categories`, `statuses`, `projects`, `locations`
- `team_members`, `job_listings`, `talks`, `awards`, `juries`, `network_entries`

#### HIGH: Missing indexes on `sort_order` columns

13 tables have a `sort_order` column used in `ORDER BY` clauses, but none are indexed:

- `categories`, `statuses`, `projects`, `project_attributes`, `media`
- `locations`, `team_members`, `team_member_bios`, `job_listings`
- `talks`, `awards`, `juries`, `network_entries`

#### HIGH: Missing indexes on `publish` columns

7 tables have a `publish` boolean used for filtering, unindexed:

- `projects`, `team_members`, `job_listings`
- `talks`, `awards`, `juries`, `network_entries`

#### MEDIUM: Missing index on `users.role`

The `role` column is used for authorization checks on every authenticated request but has no index.

#### OK: Foreign key indexes

Foreign keys created via `constrained()` get implicit indexes. Pivot tables (`category_project`, `project_status`) have proper composite unique indexes.

#### OK: UUID and slug columns

All `uuid` and `slug` columns have unique indexes.

### Recommended Migration

```php
// database/migrations/xxxx_add_performance_indexes.php

public function up(): void
{
	// CRITICAL: Polymorphic composite indexes
	Schema::table('media', function (Blueprint $table) {
		$table->index(['mediable_type', 'mediable_id']);
	});
	Schema::table('activity_log', function (Blueprint $table) {
		$table->index(['subject_type', 'subject_id']);
		$table->index(['causer_type', 'causer_id']);
	});

	// HIGH: deleted_at indexes
	$softDeletables = [
		'users', 'categories', 'statuses', 'projects', 'locations',
		'team_members', 'job_listings', 'talks', 'awards', 'juries',
		'network_entries',
	];
	foreach ($softDeletables as $t) {
		Schema::table($t, fn (Blueprint $table) => $table->index('deleted_at'));
	}

	// HIGH: sort_order indexes
	$sortables = [
		'categories', 'statuses', 'projects', 'project_attributes',
		'media', 'locations', 'team_members', 'team_member_bios',
		'job_listings', 'talks', 'awards', 'juries', 'network_entries',
	];
	foreach ($sortables as $t) {
		Schema::table($t, fn (Blueprint $table) => $table->index('sort_order'));
	}

	// HIGH: publish indexes
	$publishables = [
		'projects', 'team_members', 'job_listings',
		'talks', 'awards', 'juries', 'network_entries',
	];
	foreach ($publishables as $t) {
		Schema::table($t, fn (Blueprint $table) => $table->index('publish'));
	}

	// MEDIUM: user role
	Schema::table('users', fn (Blueprint $table) => $table->index('role'));
}
```

---

## 2. Backend Performance (Laravel)

### Eloquent & Data

- [x] Audit all API controllers for N+1 queries
- [x] Review API resources for hidden eager-load triggers
- [x] Check pagination on large collections
- [x] Review action classes for redundant queries

### Findings

#### HIGH: No pagination on Projects and Users

Only `ActivityController` uses pagination (`->paginate(50)`). Two endpoints return unbounded result sets:

- **ProjectController@index** — loads all projects with 5 eager-loaded relationships. With many projects this will cause memory issues and slow responses.
- **UserController@index** — loads all users with `->get()`.

**Recommendation:** Add `->paginate(50)` to both, matching the ActivityController pattern.

#### OK: Eager loading in most controllers

These controllers properly eager-load all needed relationships:
- `ProjectController`: `with(['attributes', 'media', 'categories', 'statuses', 'location'])`
- `TeamMemberController`: `with(['bios', 'media', 'location'])`
- `JobController`: `with('location')`
- `AwardController`: `with('project')`
- `NetworkEntryController`: `with('media')`
- `ActivityController`: `with('causer', 'subject')`

#### OK: API Resources use `whenLoaded()`

All resources properly use `$this->whenLoaded()` for conditional relationship inclusion. No hidden lazy-loading triggers.

#### NOTE: AwardResource deep nesting

`AwardResource` includes `ProjectResource` which itself includes 5 nested collections. When awards load their project relationship, the response can become very large. Consider a lightweight `ProjectSummaryResource` for this context.

#### NOTE: Authorization checks in resources

Most resources include `$request->user()?->can()` policy checks in their `toArray()` method. This adds overhead per-resource-per-request. Acceptable for small collections but could become a bottleneck at scale.

### Route & Middleware

- [x] Audit middleware stack
- [x] Review route organization

**OK:** Middleware stacks are lean:
- Dashboard SPA route: `auth` only
- API routes: `web`, `auth` — appropriate for session-based auth
- Public routes: no middleware overhead

### Caching Strategy

- [x] Evaluate cache driver for production
- [x] Audit session driver
- [x] Review Glide image caching

#### HIGH: Database used for cache, session, and queue

Current `.env` configuration:
```
CACHE_STORE=database
SESSION_DRIVER=database
QUEUE_CONNECTION=database
```

Every cache hit, session read, and queue operation triggers a database query. For production, switch all three to **Redis** for 5-10x improvement in throughput.

**Recommended production `.env`:**
```
CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

#### OK: No application-level data caching

Lookup tables (locations, categories, statuses) are queried fresh on every request. These change rarely and are good candidates for cache:

```php
// Example: cache locations for 1 hour
$locations = Cache::remember('locations', 3600, function () {
	return Location::orderBy('sort_order')->get();
});
```

### File Uploads & Media

- [x] Audit Glide configuration
- [x] Check cache headers and disk caching
- [x] Review upload configuration

#### OK: Glide caching is well-configured

- **Server-side:** Processed images cached in `storage/app/.glide-cache/`
- **Browser-side:** 1-year `Cache-Control: max-age=31536000, public` + `Expires` header
- **Driver:** ImageMagick (fast)
- **No URL signing** — potential security concern (see security audit) but not a performance issue

#### OK: Uppy upload configuration

- Max file size: 50 MB
- Accepted formats: jpg, jpeg, png, webp, gif
- Auto-proceed enabled (no manual confirm)
- XHR upload (not chunked/tus) — adequate for 50 MB limit

#### NOTE: No image optimization on upload

Uploaded images retain original quality. Consider:
- Stripping EXIF metadata
- Auto-rotating based on EXIF orientation
- Compressing on upload (quality 85-90%)
- Pre-generating common thumbnail sizes

#### NOTE: No orphaned file cleanup

Files uploaded to `temp/` that are never attached (e.g., user abandons form) remain on disk indefinitely. Consider a scheduled cleanup command.

---

## 3. Frontend Performance

### Asset Loading

- [x] Audit Vite build output and bundle sizes
- [x] Verify code splitting between public and dashboard
- [x] Check tree-shaking and Tailwind purging
- [x] Audit third-party dependency sizes

### Findings

#### OK: Separate entry points

Vite is configured with 2 entry points:
- **Public site:** `site.css` + `site.js` (Alpine.js, Swiper, custom modules)
- **Dashboard:** `app.css` + `app.js` (Vue 3, Pinia, router, all components)

Public site code does not load on dashboard and vice versa.

#### CRITICAL: No route-level code splitting

All dashboard routes use **static imports**:

```javascript
// Current: resources/js/app/router/index.js
import ProjectsIndex from '@/views/projects/Index.vue'
import ProjectsShow from '@/views/projects/Show.vue'
```

This means Uppy (~80 KB) and vuedraggable are bundled into a single JS file and loaded on **every** dashboard page, even when not needed.

**Fix — convert to lazy loading:**
```javascript
const ProjectsIndex = () => import('@/views/projects/Index.vue')
const ProjectsShow = () => import('@/views/projects/Show.vue')
```

**Estimated savings:** 35-45% reduction in initial bundle size (~150-200 KB less on first load).

#### HIGH: Vendor CSS loaded unconditionally

`resources/css/app.css` imports Uppy and TipTap CSS globally:

```css
@import "./vendor/uppy.css";
@import "./vendor/tiptap.css";
```

These styles are only needed on specific routes. Move them into the respective Vue components or lazy-loaded route modules.

#### MEDIUM: 30 icon components statically imported

`Components.vue` imports all 30 icon components. Consider an icon sprite or dynamic `defineAsyncComponent` pattern.

#### OK: Tailwind content scanning

Tailwind 4 content sources are properly configured for purging unused classes:
```css
@source '../../vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php';
@source '../../storage/framework/views/*.php';
@source '../**/*.blade.php';
@source '../**/*.js';
```

### Estimated Bundle Sizes (unoptimized)

| Package | Size (approx.) | Used on |
|---------|---------------|---------|
| Vue 3 | ~35 KB | All dashboard |
| Uppy (5 packages) | ~80 KB | Media upload only |
| Swiper | ~60 KB | Public site only |
| Vue Router | ~15 KB | All dashboard |
| vuedraggable | ~25 KB | Media grid, reorder |
| Pinia | ~3 KB | All dashboard |
| Alpine.js | ~15 KB | Public site only |

**Total dashboard bundle (estimated):** ~400-500 KB (~90-120 KB gzipped)
**After code splitting (estimated):** ~200-250 KB initial (~50-65 KB gzipped)

---

## 4. Infrastructure & Configuration

### Environment

- [x] Check debug mode and log level
- [x] Audit production readiness of configuration

### Findings

#### CRITICAL (production): APP_DEBUG must be false

Current `.env` has `APP_DEBUG=true`. In production this exposes stack traces, environment variables, and database queries. Also causes performance overhead from detailed exception handling.

#### HIGH (production): LOG_LEVEL should be warning or higher

Current `LOG_LEVEL=debug` generates excessive I/O. Set to `warning` or `error` in production.

#### Production `.env` checklist

```
APP_ENV=production
APP_DEBUG=false
LOG_LEVEL=warning
CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
MAIL_MAILER=smtp
```

### Artisan Optimization Commands

Run these in production deployment:

```bash
php artisan config:cache    # Cache config files
php artisan route:cache     # Cache route definitions
php artisan view:cache      # Cache Blade templates
php artisan event:cache     # Cache event/listener mapping
```

### PHP Configuration (check on production server)

- [ ] OPcache enabled and configured
- [ ] `expose_php = Off`
- [ ] `display_errors = Off`
- [ ] Adequate `memory_limit` (256M recommended)
- [ ] PHP-FPM worker count tuned for available RAM

### MySQL Configuration (check on production server)

- [ ] `innodb_buffer_pool_size` set to 50-70% of available RAM
- [ ] Slow query log enabled with 1s threshold
- [ ] Connection limits appropriate for expected concurrency

### Web Server (check on production server)

- [ ] gzip/brotli compression for HTML, JSON, JS, CSS
- [ ] HTTP/2 enabled
- [ ] Long-lived cache headers for hashed assets (`Cache-Control: max-age=31536000`)
- [ ] TLS 1.2+ with strong ciphers

---

## 5. Lighthouse / Web Vitals

- [ ] Run Lighthouse audit on key public pages (homepage, project detail, team page)
- [ ] Run Lighthouse audit on dashboard pages (project list, project edit form)
- [ ] Measure Core Web Vitals: LCP, FID/INP, CLS
- [ ] Check TTFB for server response time

*Requires running application — execute manually with Chrome DevTools.*

---

## 6. Load Testing

- [ ] Define expected traffic patterns (concurrent users, peak load)
- [ ] Run load test on public-facing pages
- [ ] Run load test on API endpoints (project listing with filters, media upload)
- [ ] Identify breaking points and bottlenecks under load

*Requires deployed environment — execute with k6 or Artillery.*

---

## Summary of Findings

### By Severity

| # | Severity | Area | Finding |
|---|----------|------|---------|
| 1 | CRITICAL | Database | Missing composite indexes on `media` polymorphic columns |
| 2 | CRITICAL | Database | Missing composite indexes on `activity_log` polymorphic columns |
| 3 | CRITICAL | Frontend | No route-level code splitting — TipTap + Uppy in main bundle |
| 4 | CRITICAL | Infra | `APP_DEBUG=true` in production |
| 5 | HIGH | Database | Missing indexes on `deleted_at` (11 tables) |
| 6 | HIGH | Database | Missing indexes on `sort_order` (13 tables) |
| 7 | HIGH | Database | Missing indexes on `publish` (7 tables) |
| 8 | HIGH | Backend | No pagination on ProjectController and UserController |
| 9 | HIGH | Backend | Cache, session, queue all using database driver |
| 10 | HIGH | Frontend | Vendor CSS (Uppy, TipTap) loaded unconditionally |
| 11 | HIGH | Infra | `LOG_LEVEL=debug` in production |
| 12 | MEDIUM | Database | Missing index on `users.role` |
| 13 | MEDIUM | Backend | No data caching for lookup tables |
| 14 | MEDIUM | Backend | No orphaned temp file cleanup |
| 15 | MEDIUM | Backend | No image optimization on upload |
| 16 | MEDIUM | Frontend | 30 icon components statically imported |
| 17 | LOW | Backend | AwardResource deep nesting (Project with 5 relations) |
| 18 | LOW | Backend | Per-resource policy checks in API resources |

### What's Working Well

- Separate Vite entry points for public site vs dashboard
- Consistent eager loading in most controllers (Project, TeamMember, Job, Award, NetworkEntry, Activity)
- Proper use of `whenLoaded()` in all API resources
- Lean middleware stacks — no unnecessary middleware on routes
- Glide image caching with 1-year browser cache + server-side disk cache
- Tailwind content scanning properly configured
- Pivot tables well-indexed with composite unique constraints
- UUID and slug columns properly indexed

---

## Priority Implementation Order

1. **Database indexes migration** — single migration, biggest impact, zero risk
2. **ProjectController + UserController pagination** — 2 lines of code
3. **Route-level code splitting** — convert static imports to lazy imports in router
4. **Move vendor CSS into lazy-loaded components** — remove from global app.css
5. **Production .env configuration** — Redis for cache/session/queue, debug off
6. **Artisan cache commands in deploy script** — config:cache, route:cache, view:cache
7. **Lookup table caching** — Cache::remember for locations, categories, statuses
8. **Temp file cleanup command** — scheduled artisan command
9. **Lighthouse audit** — manual, after above fixes are deployed

---

## Tools

| Tool | Purpose |
|------|---------|
| Laravel Debugbar | Query logging, N+1 detection, memory usage |
| Laravel Telescope | Request profiling, slow queries, cache hits |
| Lighthouse (Chrome DevTools) | Frontend performance, Web Vitals |
| Vite Bundle Analyzer (`rollup-plugin-visualizer`) | JS/CSS bundle size analysis |
| mysqltuner | MySQL configuration review |
| k6 / Artillery | Load testing |
