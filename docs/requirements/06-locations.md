# Locations Module

## Overview

Manageable list of office locations (Zürich, Berlin, etc.) used for filtering projects and team members.

---

## Model

### Location

| Field | Type | Description |
|-------|------|-------------|
| id | bigint | Primary key (internal) |
| uuid | uuid | Unique, public identifier |
| title | string | Location name (e.g., "Zürich") |
| slug | string | URL-friendly, unique |
| sort_order | integer | Display order |
| created_at | timestamp | |
| updated_at | timestamp | |
| deleted_at | timestamp | Soft delete |

**Relationships:**
- `hasMany` → Project
- `hasMany` → TeamMember
- `hasMany` → Job

---

## Routes

### API

```
GET    /api/dashboard/locations           → List all
POST   /api/dashboard/locations           → Create
GET    /api/dashboard/locations/{uuid}    → Get single
PUT    /api/dashboard/locations/{uuid}    → Update
DELETE /api/dashboard/locations/{uuid}    → Soft delete
POST   /api/dashboard/locations/{uuid}/restore → Restore
PATCH  /api/dashboard/locations/reorder   → Reorder
```

---

## API Endpoints

### List Locations

**GET** `/api/dashboard/locations`

**Query Parameters:**
| Param | Type | Description |
|-------|------|-------------|
| trashed | bool | Include soft-deleted |

**Response:**
```json
{
  "data": [
    {
      "uuid": "550e8400-e29b-41d4-a716-446655440000",
      "title": "Zürich",
      "slug": "zuerich",
      "sort_order": 1,
      "projects_count": 35,
      "team_members_count": 18
    },
    {
      "uuid": "6ba7b810-9dad-11d1-80b4-00c04fd430c8",
      "title": "Berlin",
      "slug": "berlin",
      "sort_order": 2,
      "projects_count": 12,
      "team_members_count": 6
    }
  ]
}
```

### Get Location

**GET** `/api/dashboard/locations/{uuid}`

**Response:**
```json
{
  "data": {
    "uuid": "550e8400-e29b-41d4-a716-446655440000",
    "title": "Zürich",
    "slug": "zuerich",
    "sort_order": 1,
    "created_at": "...",
    "updated_at": "..."
  }
}
```

### Store Location

**POST** `/api/dashboard/locations`

**Request:**
```json
{
  "title": "Basel",
  "slug": "basel"
}
```

**Validation:**
```php
[
    'title' => 'required|string|max:255',
    'slug' => 'required|string|max:255|unique:locations,slug',
]
```

### Update Location

**PUT** `/api/dashboard/locations/{uuid}`

**Validation:**
```php
[
    'title' => 'required|string|max:255',
    'slug' => 'required|string|max:255|unique:locations,slug,' . $location->id,
]
```

### Delete Location

**DELETE** `/api/dashboard/locations/{uuid}`

**Rules:**
- Cannot delete if projects or team members are assigned
- Returns error with count of related entities

**Error Response:**
```json
{
  "message": "Location cannot be deleted",
  "errors": {
    "location": ["This location has 35 projects and 18 team members assigned. Reassign them first."]
  }
}
```

### Restore Location

**POST** `/api/dashboard/locations/{uuid}/restore`

Admin only.

### Reorder Locations

**PATCH** `/api/dashboard/locations/reorder`

**Request:**
```json
{
  "items": [
    { "uuid": "550e8400-...", "sort_order": 1 },
    { "uuid": "6ba7b810-...", "sort_order": 2 }
  ]
}
```

---

## Migration

### Database Migration

```php
Schema::create('locations', function (Blueprint $table) {
    $table->id();
    $table->uuid('uuid')->unique();
    $table->string('title');
    $table->string('slug')->unique();
    $table->integer('sort_order')->default(0);
    $table->timestamps();
    $table->softDeletes();
});
```

### Update Existing Tables

Projects and Team Members currently use `location` as an enum (`zurich`, `berlin`). Migrate to foreign key:

```php
// Migration: update_projects_location_to_foreign_key

Schema::table('projects', function (Blueprint $table) {
    // Add new column
    $table->foreignId('location_id')->nullable()->after('description');
});

// Data migration in seeder/command:
// Map 'zurich' -> Location ID 1, 'berlin' -> Location ID 2

Schema::table('projects', function (Blueprint $table) {
    // Drop old column
    $table->dropColumn('location');
    // Make foreign key required
    $table->foreignId('location_id')->nullable(false)->change();
});
```

---

## Seeder

```php
// database/seeders/LocationSeeder.php

public function run(): void
{
    Location::create(['title' => 'Zürich', 'slug' => 'zuerich', 'sort_order' => 1]);
    Location::create(['title' => 'Berlin', 'slug' => 'berlin', 'sort_order' => 2]);
}
```

---

## UI

### Location Management

Simple inline management (no separate page needed):

- List view with drag-drop reorder
- Inline edit (click to edit title)
- Add new location form
- Delete with confirmation (shows related counts)

Can be placed in Settings or as a modal from filter dropdowns.

### Location Selector (in forms)

Dropdown component used in:
- Project form
- Team member form
- Job form

```vue
<LocationSelect v-model="form.location_id" />
```

---

## Frontend Integration

Update Livewire components to use database locations:

### Works.php (Livewire)

```php
// Before: hardcoded locations
public function mount()
{
    $this->locations = ['zuerich', 'berlin'];
}

// After: from database
public function mount()
{
    $this->locations = Location::ordered()->pluck('title', 'slug');
}
```

### Team.php (Livewire)

Same pattern - fetch locations from database.

---

## Business Rules

1. Slug must be unique and URL-safe
2. Auto-generate slug from title if not provided
3. Cannot delete location with assigned content
4. Sort order determines display order in dropdowns
5. At least one location must exist (prevent deleting last one)
