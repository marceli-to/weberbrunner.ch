# Media & Uploads

## Overview

Polymorphic media system for handling images across all entities. Uses Uppy for chunked uploads with custom styling.

---

## Model

### Media

| Field | Type | Description |
|-------|------|-------------|
| id | bigint | Primary key (internal) |
| uuid | uuid | Unique, public identifier |
| mediable_type | string | Polymorphic type (e.g., `App\Models\Project`) |
| mediable_id | bigint | Polymorphic ID |
| file | string | Relative path to file |
| original_name | string | Original filename |
| mime_type | string | File MIME type |
| size | integer | File size in bytes |
| width | integer | Nullable, image width |
| height | integer | Nullable, image height |
| alt | string | Nullable, alt text |
| caption | string | Nullable, caption |
| is_teaser | boolean | Default: false (for projects) |
| sort_order | integer | Display order |
| created_at | timestamp | |
| updated_at | timestamp | |

**Notes:**
- No soft delete (media is permanently deleted)
- Files are deleted from disk when record is deleted

**Relationships:**
- `morphTo` → mediable (Project, TeamMember, NetworkEntry)

**Computed Attributes:**
- `url` - Full URL to image
- `orientation` - `landscape`, `portrait`, `square` based on dimensions

---

## Storage

### Configuration

- **Disk:** Local (`storage/app/public`)
- **Public access:** Via symbolic link (`php artisan storage:link`)
- **Directory structure:**
  ```
  storage/app/public/
  ├── projects/
  │   ├── {project_id}/
  │   │   ├── image1.jpg
  │   │   └── image2.jpg
  ├── team/
  │   ├── {member_id}/
  │   │   └── portrait.jpg
  └── network/
      └── {entry_id}/
          └── logo.png
  ```

### Image Processing

Existing Glide integration handles on-the-fly transformations:
- URL: `/img/{path}?w=800&h=600&fit=crop`
- Caching: 1 year browser cache
- Supported params: `w`, `h`, `fit`, `q` (quality)

---

## Upload Component

### Library: Uppy

**Why Uppy:**
- Chunked uploads for large files
- Resumable uploads
- Progress indication
- Drag-and-drop support
- Highly customizable/styleable
- Good Vue 3 integration

### Installation

```bash
npm install @uppy/core @uppy/dashboard @uppy/xhr-upload @uppy/image-editor
```

### Vue Component

```vue
<!-- resources/js/dashboard/components/media/MediaUploader.vue -->

<template>
  <div ref="uppyContainer" class="uppy-container"></div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import Uppy from '@uppy/core'
import Dashboard from '@uppy/dashboard'
import XHRUpload from '@uppy/xhr-upload'

const props = defineProps({
  endpoint: { type: String, required: true },
  maxFiles: { type: Number, default: 10 },
  allowedTypes: { type: Array, default: () => ['image/*'] },
  maxFileSize: { type: Number, default: 50 * 1024 * 1024 } // 50MB
})

const emit = defineEmits(['upload-success', 'upload-error'])

const uppyContainer = ref(null)
let uppy = null

onMounted(() => {
  uppy = new Uppy({
    restrictions: {
      maxNumberOfFiles: props.maxFiles,
      allowedFileTypes: props.allowedTypes,
      maxFileSize: props.maxFileSize
    }
  })
  .use(Dashboard, {
    inline: true,
    target: uppyContainer.value,
    hideUploadButton: false,
    showProgressDetails: true,
    proudlyDisplayPoweredByUppy: false,
    locale: {
      strings: {
        dropPasteFiles: 'Dateien hierher ziehen oder %{browseFiles}',
        browseFiles: 'durchsuchen',
        uploadComplete: 'Upload abgeschlossen',
        // ... more German translations
      }
    }
  })
  .use(XHRUpload, {
    endpoint: props.endpoint,
    formData: true,
    fieldName: 'file',
    headers: {
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    }
  })

  uppy.on('upload-success', (file, response) => {
    emit('upload-success', response.body)
  })

  uppy.on('upload-error', (file, error) => {
    emit('upload-error', error)
  })
})

onUnmounted(() => {
  uppy?.destroy()
})
</script>

<style>
/* Custom Uppy styling - minimal overrides */
.uppy-container {
  @apply rounded-lg border border-gray-200;
}

.uppy-Dashboard-inner {
  @apply bg-gray-50;
}

/* Add more custom styles as needed */
</style>
```

### Chunked Upload (for very large files)

If needed, use Tus protocol:

```bash
npm install @uppy/tus
```

```js
import Tus from '@uppy/tus'

uppy.use(Tus, {
  endpoint: '/api/dashboard/upload/tus',
  chunkSize: 5 * 1024 * 1024 // 5MB chunks
})
```

Backend: Use `ankitpokhrel/tus-php` package.

---

## API Endpoints

### Upload Media

**POST** `/api/dashboard/media/upload`

**Request:** `multipart/form-data`
- `file` - The uploaded file
- `mediable_type` - e.g., `project`, `team`, `network`
- `mediable_uuid` - UUID of parent entity

**Response:**
```json
{
  "data": {
    "uuid": "550e8400-e29b-41d4-a716-446655440000",
    "file": "projects/1/image.jpg",
    "url": "/storage/projects/1/image.jpg",
    "original_name": "photo.jpg",
    "mime_type": "image/jpeg",
    "size": 1548576,
    "width": 1920,
    "height": 1080,
    "orientation": "landscape"
  }
}
```

### Update Media

**PUT** `/api/dashboard/media/{uuid}`

**Request:**
```json
{
  "alt": "Project exterior view",
  "caption": "Photo by John Doe",
  "is_teaser": true
}
```

### Delete Media

**DELETE** `/api/dashboard/media/{uuid}`

Permanently deletes record and file from disk.

### Reorder Media

**PATCH** `/api/dashboard/media/reorder`

**Request:**
```json
{
  "items": [
    { "uuid": "550e8400-...", "sort_order": 1 },
    { "uuid": "6ba7b810-...", "sort_order": 2 },
    { "uuid": "6ba7b811-...", "sort_order": 3 }
  ]
}
```

### Set Teaser

**PATCH** `/api/dashboard/media/{uuid}/teaser`

Sets this media as teaser and unsets any previous teaser for the same entity.

---

## Upload Handling (Backend)

### Controller

```php
// app/Http/Controllers/Dashboard/MediaController.php

public function upload(UploadMediaRequest $request, StoreMediaAction $action)
{
    $media = $action->execute(
        file: $request->file('file'),
        mediableType: $request->input('mediable_type'),
        mediableId: $request->input('mediable_id')
    );

    return new MediaResource($media);
}
```

### Action

```php
// app/Actions/Dashboard/Media/StoreMediaAction.php

class StoreMediaAction
{
    public function execute(UploadedFile $file, string $mediableType, int $mediableId): Media
    {
        $model = $this->resolveModel($mediableType, $mediableId);

        // Generate path
        $path = $this->generatePath($mediableType, $mediableId);

        // Store file
        $filename = $this->generateFilename($file);
        $file->storeAs($path, $filename, 'public');

        // Get dimensions for images
        $dimensions = $this->getImageDimensions($file);

        // Create media record
        return $model->media()->create([
            'file' => $path . '/' . $filename,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'width' => $dimensions['width'] ?? null,
            'height' => $dimensions['height'] ?? null,
            'sort_order' => $model->media()->max('sort_order') + 1,
        ]);
    }

    private function resolveModel(string $type, int $id): Model
    {
        return match($type) {
            'project' => Project::findOrFail($id),
            'team' => TeamMember::findOrFail($id),
            'network' => NetworkEntry::findOrFail($id),
            default => throw new \InvalidArgumentException("Invalid mediable type: {$type}")
        };
    }
}
```

### Validation

```php
// app/Http/Requests/Dashboard/UploadMediaRequest.php

public function rules(): array
{
    return [
        'file' => [
            'required',
            'file',
            'mimes:jpg,jpeg,png,webp,gif',
            'max:51200', // 50MB in KB
        ],
        'mediable_type' => 'required|in:project,team,network',
        'mediable_id' => 'required|integer',
    ];
}
```

---

## Allowed File Types

| Type | Extensions | Max Size |
|------|------------|----------|
| Images | jpg, jpeg, png, webp, gif | 50 MB |

**Notes:**
- PDFs not needed for media (only images)
- WebP supported for modern browsers
- GIF supported for potential animations

---

## Image Processing on Upload

Optionally process images on upload:

1. **Strip EXIF data** (privacy)
2. **Auto-rotate** based on EXIF orientation
3. **Generate dimensions** (width/height stored in DB)
4. **Optional: Generate thumbnails** (or rely on Glide for on-demand)

```php
// In StoreMediaAction

private function processImage(UploadedFile $file): void
{
    $image = Image::make($file->path());

    // Auto-rotate based on EXIF
    $image->orientate();

    // Strip EXIF data
    $image->save(null, 90);
}
```

---

## UI Components

### MediaGrid

Displays uploaded media with:
- Thumbnail preview
- Alt text badge
- Teaser indicator (star icon)
- Drag handles for reordering
- Edit/Delete buttons

```vue
<!-- MediaGrid.vue -->
<template>
  <draggable v-model="items" item-key="id" @end="onReorder">
    <template #item="{ element }">
      <div class="media-item">
        <img :src="element.url + '?w=200&h=200&fit=crop'" :alt="element.alt">
        <div class="media-actions">
          <button @click="setTeaser(element)" :class="{ active: element.is_teaser }">
            <!-- Star icon -->
          </button>
          <button @click="editMedia(element)">
            <!-- Edit icon -->
          </button>
          <button @click="deleteMedia(element)">
            <!-- Delete icon -->
          </button>
        </div>
      </div>
    </template>
  </draggable>
</template>
```

### MediaEditModal

Modal for editing alt text and caption:

```vue
<template>
  <Modal v-model="isOpen">
    <form @submit.prevent="save">
      <input v-model="form.alt" placeholder="Alt text" />
      <textarea v-model="form.caption" placeholder="Caption"></textarea>
      <button type="submit">Speichern</button>
    </form>
  </Modal>
</template>
```

---

## Cleanup

### Orphaned Files

Scheduled command to clean up orphaned files (files on disk without DB record):

```php
// app/Console/Commands/CleanupOrphanedMedia.php

// Run weekly via scheduler
```

### Deleted Entities

When a parent entity is deleted:
- **Soft delete:** Media preserved (for potential restore)
- **Hard delete:** Media files and records removed

```php
// In Project model

protected static function booted()
{
    static::forceDeleting(function ($project) {
        foreach ($project->media as $media) {
            Storage::disk('public')->delete($media->file);
            $media->delete();
        }
    });
}
```
