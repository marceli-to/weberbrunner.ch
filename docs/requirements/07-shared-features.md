# Shared Features

## Overview

Cross-cutting features used by multiple entities:
- Soft deletes (trash/restore)
- Drag-and-drop sorting
- Activity logging

---

## Soft Deletes

### Implementation

Laravel's built-in `SoftDeletes` trait on all content models:

```php
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;
}
```

### Entities with Soft Deletes

| Entity | Soft Delete |
|--------|-------------|
| User | Yes |
| Project | Yes |
| Category | Yes |
| Status | Yes |
| TeamMember | Yes |
| Job | Yes |
| Talk | Yes |
| Award | Yes |
| Jury | Yes |
| NetworkEntry | Yes |
| Location | Yes |
| Media | No (permanent delete) |
| ProjectAttribute | No (deleted with project) |
| TeamMemberBio | No (deleted with member) |

### UI: Trash View

Each entity list can toggle "Show deleted" (admin only):

```vue
<template>
  <div>
    <!-- Admin only -->
    <label v-if="isAdmin">
      <input type="checkbox" v-model="showTrashed" />
      Gelöschte anzeigen
    </label>

    <table>
      <tr v-for="item in items" :class="{ 'opacity-50': item.deleted_at }">
        <td>{{ item.title }}</td>
        <td>
          <template v-if="item.deleted_at">
            <button @click="restore(item)">Wiederherstellen</button>
            <button @click="forceDelete(item)">Endgültig löschen</button>
          </template>
          <template v-else>
            <button @click="edit(item)">Bearbeiten</button>
            <button @click="delete(item)">Löschen</button>
          </template>
        </td>
      </tr>
    </table>
  </div>
</template>
```

### API Pattern

```php
// Controller

public function index(Request $request)
{
    $query = Project::query();

    if ($request->boolean('trashed') && $request->user()->isAdmin()) {
        $query->withTrashed();
    }

    return ProjectResource::collection($query->paginate());
}

public function destroy(Project $project)
{
    $this->authorize('delete', $project);
    $project->delete(); // Soft delete
    return response()->noContent();
}

public function restore(int $id)
{
    $project = Project::withTrashed()->findOrFail($id);
    $this->authorize('restore', $project);
    $project->restore();
    return new ProjectResource($project);
}

public function forceDelete(int $id)
{
    $project = Project::withTrashed()->findOrFail($id);
    $this->authorize('forceDelete', $project);
    $project->forceDelete();
    return response()->noContent();
}
```

### Routes

```php
// For each entity with soft deletes
Route::post('{entity}/{id}/restore', [Controller::class, 'restore']);
Route::delete('{entity}/{id}/force', [Controller::class, 'forceDelete']);
```

---

## Drag-and-Drop Sorting

### Implementation

All sortable entities have a `sort_order` integer column.

### Database

```php
Schema::table('projects', function (Blueprint $table) {
    $table->integer('sort_order')->default(0);
});
```

### Model Trait

```php
// app/Traits/Sortable.php

trait Sortable
{
    public static function bootSortable(): void
    {
        static::creating(function ($model) {
            if (is_null($model->sort_order)) {
                $model->sort_order = static::max('sort_order') + 1;
            }
        });
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }
}
```

Usage:

```php
class Project extends Model
{
    use Sortable;
}
```

### API Endpoint

**PATCH** `/api/dashboard/{entity}/reorder`

```php
// app/Http/Controllers/Concerns/Reorderable.php

trait Reorderable
{
    public function reorder(ReorderRequest $request)
    {
        $this->authorize('update', $this->model);

        foreach ($request->input('items') as $item) {
            $this->model::where('id', $item['id'])
                ->update(['sort_order' => $item['sort_order']]);
        }

        return response()->json(['message' => 'Order updated']);
    }
}
```

### Request Validation

```php
// app/Http/Requests/ReorderRequest.php

public function rules(): array
{
    return [
        'items' => 'required|array',
        'items.*.id' => 'required|integer',
        'items.*.sort_order' => 'required|integer|min:0',
    ];
}
```

### Vue Component

Using `vuedraggable` (Vue 3 compatible):

```bash
npm install vuedraggable@next
```

```vue
<!-- components/ui/SortableList.vue -->

<template>
  <draggable
    v-model="items"
    item-key="id"
    handle=".drag-handle"
    @end="onDragEnd"
  >
    <template #item="{ element }">
      <div class="sortable-item">
        <span class="drag-handle cursor-move">⋮⋮</span>
        <slot :item="element" />
      </div>
    </template>
  </draggable>
</template>

<script setup>
import { ref, watch } from 'vue'
import draggable from 'vuedraggable'
import { debounce } from 'lodash-es'

const props = defineProps({
  modelValue: Array,
  endpoint: String
})

const emit = defineEmits(['update:modelValue'])

const items = ref(props.modelValue)

watch(() => props.modelValue, (val) => {
  items.value = val
})

const saveOrder = debounce(async () => {
  const payload = items.value.map((item, index) => ({
    id: item.id,
    sort_order: index + 1
  }))

  await axios.patch(props.endpoint, { items: payload })
}, 500)

const onDragEnd = () => {
  emit('update:modelValue', items.value)
  saveOrder()
}
</script>
```

### Entities with Sorting

| Entity | Sortable |
|--------|----------|
| Project | Yes |
| Category | Yes |
| Status | Yes |
| TeamMember | Yes |
| Job | Yes |
| Talk | Yes (within year) |
| Award | Yes (within year) |
| Jury | Yes (within year) |
| NetworkEntry | Yes |
| Location | Yes |
| ProjectAttribute | Yes |
| TeamMemberBio | Yes |
| Media | Yes |

---

## Activity Logging

### Package

Use `spatie/laravel-activitylog`:

```bash
composer require spatie/laravel-activitylog
php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="activitylog-migrations"
php artisan migrate
```

### Configuration

```php
// config/activitylog.php

return [
    'default_log_name' => 'default',
    'default_auth_driver' => null,
    'subject_returns_soft_deleted_models' => true,
    'activity_model' => \Spatie\Activitylog\Models\Activity::class,
];
```

### Model Setup

```php
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Project extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['title', 'slug', 'description', 'location_id', 'publish'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
```

### What Gets Logged

| Event | Logged |
|-------|--------|
| Create | Yes |
| Update | Yes (only changed fields) |
| Delete | Yes |
| Restore | Yes |
| Login | Yes (custom) |

### Logged Entities

All content models (Project, TeamMember, Job, Talk, Award, Jury, NetworkEntry, Category, Status, Location).

### Custom Events

```php
// Log custom events

activity()
    ->causedBy($user)
    ->performedOn($project)
    ->withProperties(['media_id' => $media->id])
    ->log('media_uploaded');
```

### Activity Log UI

Admin-only view showing recent activity:

**Route:** `GET /dashboard/activity`

**Features:**
- Filter by entity type
- Filter by user
- Filter by date range
- Show what changed (diff)

```vue
<!-- pages/Activity.vue -->

<template>
  <div>
    <h1>Aktivitätsprotokoll</h1>

    <div class="filters">
      <select v-model="filters.subject_type">
        <option value="">Alle Typen</option>
        <option value="Project">Projekte</option>
        <option value="TeamMember">Team</option>
        <!-- ... -->
      </select>

      <select v-model="filters.causer_id">
        <option value="">Alle Benutzer</option>
        <option v-for="user in users" :value="user.id">
          {{ user.name }}
        </option>
      </select>
    </div>

    <table>
      <thead>
        <tr>
          <th>Datum</th>
          <th>Benutzer</th>
          <th>Aktion</th>
          <th>Objekt</th>
          <th>Änderungen</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="activity in activities">
          <td>{{ formatDate(activity.created_at) }}</td>
          <td>{{ activity.causer?.name }}</td>
          <td>{{ translateEvent(activity.event) }}</td>
          <td>
            <a :href="getSubjectUrl(activity)">
              {{ activity.subject?.title }}
            </a>
          </td>
          <td>
            <ActivityDiff :properties="activity.properties" />
          </td>
        </tr>
      </tbody>
    </table>

    <Pagination :meta="meta" @page-change="loadPage" />
  </div>
</template>
```

### API Endpoint

**GET** `/api/dashboard/activity`

**Query Parameters:**
| Param | Type | Description |
|-------|------|-------------|
| page | int | Page number |
| per_page | int | Items per page |
| subject_type | string | Filter by model type |
| causer_id | int | Filter by user ID |
| from | date | Start date |
| to | date | End date |

**Response:**
```json
{
  "data": [
    {
      "id": 150,
      "log_name": "default",
      "description": "updated",
      "subject_type": "App\\Models\\Project",
      "subject_id": 5,
      "causer_type": "App\\Models\\User",
      "causer_id": 1,
      "properties": {
        "old": { "title": "Old Title" },
        "attributes": { "title": "New Title" }
      },
      "created_at": "2024-01-15T14:30:00Z",
      "causer": {
        "id": 1,
        "name": "Admin User"
      },
      "subject": {
        "id": 5,
        "title": "New Title"
      }
    }
  ],
  "meta": { ... }
}
```

---

## Frontend Permissions

### Pattern

The backend is the single source of truth for permissions. API Resources include a `can` object with computed permissions for the current user:

```php
// app/Http/Resources/Dashboard/ProjectResource.php

public function toArray($request): array
{
    return [
        'uuid' => $this->uuid,
        'title' => $this->title,
        'slug' => $this->slug,
        // ... other fields

        'can' => [
            'update' => $request->user()->can('update', $this->resource),
            'delete' => $request->user()->can('delete', $this->resource),
            'restore' => $request->user()->can('restore', $this->resource),
            'forceDelete' => $request->user()->can('forceDelete', $this->resource),
        ],
    ];
}
```

### Vue Usage

Use `v-if` to conditionally render actions based on permissions:

```vue
<template>
  <div class="actions">
    <button v-if="project.can.update" @click="edit(project)">
      Bearbeiten
    </button>

    <button v-if="project.can.delete" @click="delete(project)">
      Löschen
    </button>

    <!-- For trashed items -->
    <template v-if="project.deleted_at">
      <button v-if="project.can.restore" @click="restore(project)">
        Wiederherstellen
      </button>
      <button v-if="project.can.forceDelete" @click="forceDelete(project)">
        Endgültig löschen
      </button>
    </template>
  </div>
</template>
```

### Benefits

- **Single source of truth**: Permission logic stays in Laravel Policies
- **No duplication**: Frontend doesn't need to know role hierarchies
- **Flexible**: Adding new roles requires no frontend changes
- **Secure**: Backend always validates (frontend is just UX)

### Entities with Permissions

All content resources include the `can` object:
- ProjectResource
- TeamMemberResource
- JobResource
- TalkResource
- AwardResource
- JuryResource
- NetworkEntryResource
- CategoryResource
- StatusResource
- LocationResource

---

## Summary

| Feature | Admin | Editor | Viewer |
|---------|-------|--------|--------|
| View content | Yes | Yes | Yes |
| Create/Edit | Yes | Yes | No |
| Delete | Yes | No | No |
| View trash | Yes | No | No |
| Restore deleted | Yes | No | No |
| Force delete | Yes | No | No |
| Reorder items | Yes | Yes | No |
| View activity log | Yes | No | No |
