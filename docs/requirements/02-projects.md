# Projects Module

## Overview

Projects are the primary content type. Each project has:
- Basic metadata (title, description, location)
- Dynamic attributes (key-value pairs)
- Media/images (teaser + gallery)
- Categories (many-to-many)
- Statuses (many-to-many)

---

## Models

### Project

| Field | Type | Description |
|-------|------|-------------|
| id | bigint | Primary key (internal) |
| uuid | uuid | Unique, public identifier |
| title | string | Project title |
| slug | string | URL-friendly identifier, unique |
| description | text | Nullable, rich text |
| location_id | foreignId | References `locations` table |
| publish | boolean | Default: false |
| sort_order | integer | For drag-drop ordering |
| created_at | timestamp | |
| updated_at | timestamp | |
| deleted_at | timestamp | Soft delete |

**Relationships:**
- `hasMany` → ProjectAttribute
- `morphMany` → Media
- `belongsToMany` → Category
- `belongsToMany` → Status
- `belongsTo` → Location

### ProjectAttribute

| Field | Type | Description |
|-------|------|-------------|
| id | bigint | Primary key (internal) |
| uuid | uuid | Unique, public identifier |
| project_id | foreignId | References `projects` |
| label | string | Attribute name (e.g., "Bauherr") |
| value | text | Attribute value |
| sort_order | integer | Display order |
| created_at | timestamp | |
| updated_at | timestamp | |

**Notes:**
- No soft delete (deleted with project or individually)
- Sortable via drag-drop

### Category

| Field | Type | Description |
|-------|------|-------------|
| id | bigint | Primary key (internal) |
| uuid | uuid | Unique, public identifier |
| title | string | Category name |
| slug | string | URL-friendly, unique |
| sort_order | integer | Display order |
| created_at | timestamp | |
| updated_at | timestamp | |
| deleted_at | timestamp | Soft delete |

**Pivot table:** `category_project`
- `category_id`
- `project_id`

### Status

| Field | Type | Description |
|-------|------|-------------|
| id | bigint | Primary key (internal) |
| uuid | uuid | Unique, public identifier |
| title | string | Status name |
| slug | string | URL-friendly, unique |
| sort_order | integer | Display order |
| created_at | timestamp | |
| updated_at | timestamp | |
| deleted_at | timestamp | Soft delete |

**Pivot table:** `project_status`
- `project_id`
- `status_id`

---

## Routes

### Web (Blade views)

```
GET    /dashboard/projects               → Index (list view)
GET    /dashboard/projects/create        → Create form
GET    /dashboard/projects/{uuid}/edit   → Edit form
```

### API (Vue.js + Axios)

```
GET    /api/dashboard/projects           → List with pagination/filters
POST   /api/dashboard/projects           → Store new project
GET    /api/dashboard/projects/{uuid}    → Get single project
PUT    /api/dashboard/projects/{uuid}    → Update project
DELETE /api/dashboard/projects/{uuid}    → Soft delete project
POST   /api/dashboard/projects/{uuid}/restore → Restore deleted
PATCH  /api/dashboard/projects/reorder   → Update sort order (bulk)
```

---

## API Endpoints

### List Projects

**GET** `/api/dashboard/projects`

**Query Parameters:**
| Param | Type | Description |
|-------|------|-------------|
| page | int | Page number |
| per_page | int | Items per page (default: 20) |
| search | string | Search in title/description |
| category | uuid/array | Filter by category UUID(s) |
| status | uuid/array | Filter by status UUID(s) |
| location | uuid | Filter by location UUID |
| publish | bool | Filter by publish status |
| trashed | bool | Include soft-deleted |

**Response:**
```json
{
  "data": [
    {
      "uuid": "550e8400-e29b-41d4-a716-446655440000",
      "title": "Wohnüberbauung Zürich",
      "slug": "wohnueberbauung-zuerich",
      "description": "...",
      "publish": true,
      "location": {
        "uuid": "6ba7b810-9dad-11d1-80b4-00c04fd430c8",
        "title": "Zürich"
      },
      "teaser_image": {
        "uuid": "6ba7b811-9dad-11d1-80b4-00c04fd430c8",
        "url": "/img/projects/teaser.jpg",
        "alt": "..."
      },
      "categories": [...],
      "statuses": [...],
      "created_at": "2024-01-15T10:30:00Z"
    }
  ],
  "meta": { ... }
}
```

### Get Project

**GET** `/api/dashboard/projects/{uuid}`

**Response:**
```json
{
  "data": {
    "uuid": "550e8400-e29b-41d4-a716-446655440000",
    "title": "Wohnüberbauung Zürich",
    "slug": "wohnueberbauung-zuerich",
    "description": "...",
    "publish": true,
    "sort_order": 5,
    "location": { "uuid": "6ba7b810-9dad-11d1-80b4-00c04fd430c8", "title": "Zürich" },
    "attributes": [
      { "uuid": "...", "label": "Bauherr", "value": "Stadt Zürich", "sort_order": 1 },
      { "uuid": "...", "label": "Jahr", "value": "2023", "sort_order": 2 }
    ],
    "media": [
      { "uuid": "...", "file": "...", "alt": "...", "is_teaser": true, "sort_order": 1 },
      { "uuid": "...", "file": "...", "alt": "...", "is_teaser": false, "sort_order": 2 }
    ],
    "categories": [
      { "uuid": "...", "title": "Wohnungsbau", "slug": "wohnungsbau" }
    ],
    "statuses": [
      { "uuid": "...", "title": "Realisiert", "slug": "realisiert" }
    ],
    "created_at": "...",
    "updated_at": "..."
  }
}
```

### Store Project

**POST** `/api/dashboard/projects`

**Request Body:**
```json
{
  "title": "Neues Projekt",
  "slug": "neues-projekt",
  "description": "Beschreibung...",
  "location_uuid": "6ba7b810-9dad-11d1-80b4-00c04fd430c8",
  "publish": false,
  "categories": ["uuid-1", "uuid-2"],
  "statuses": ["uuid-1"],
  "attributes": [
    { "label": "Bauherr", "value": "Max Muster" },
    { "label": "Jahr", "value": "2024" }
  ]
}
```

**Validation:**
```php
[
    'title' => 'required|string|max:255',
    'slug' => 'required|string|max:255|unique:projects,slug',
    'description' => 'nullable|string',
    'location_uuid' => 'required|exists:locations,uuid',
    'publish' => 'boolean',
    'categories' => 'array',
    'categories.*' => 'exists:categories,uuid',
    'statuses' => 'array',
    'statuses.*' => 'exists:statuses,uuid',
    'attributes' => 'array',
    'attributes.*.label' => 'required|string|max:255',
    'attributes.*.value' => 'required|string',
]
```

### Update Project

**PUT** `/api/dashboard/projects/{uuid}`

Same structure as store, with slug uniqueness check excluding current record.

### Delete Project

**DELETE** `/api/dashboard/projects/{uuid}`

Soft deletes the project. Associated attributes and media are NOT deleted (preserved for restore).

### Restore Project

**POST** `/api/dashboard/projects/{uuid}/restore`

Restores a soft-deleted project. Admin only.

### Reorder Projects

**PATCH** `/api/dashboard/projects/reorder`

**Request Body:**
```json
{
  "items": [
    { "uuid": "550e8400-...", "sort_order": 1 },
    { "uuid": "6ba7b810-...", "sort_order": 2 },
    { "uuid": "6ba7b811-...", "sort_order": 3 }
  ]
}
```

---

## Categories CRUD

### Routes

```
GET    /api/dashboard/categories           → List all
POST   /api/dashboard/categories           → Create
PUT    /api/dashboard/categories/{uuid}    → Update
DELETE /api/dashboard/categories/{uuid}    → Soft delete
PATCH  /api/dashboard/categories/reorder   → Reorder
```

### Validation

```php
[
    'title' => 'required|string|max:255',
    'slug' => 'required|string|max:255|unique:categories,slug',
]
```

---

## Statuses CRUD

### Routes

```
GET    /api/dashboard/statuses           → List all
POST   /api/dashboard/statuses           → Create
PUT    /api/dashboard/statuses/{uuid}    → Update
DELETE /api/dashboard/statuses/{uuid}    → Soft delete
PATCH  /api/dashboard/statuses/reorder   → Reorder
```

### Validation

```php
[
    'title' => 'required|string|max:255',
    'slug' => 'required|string|max:255|unique:statuses,slug',
]
```

---

## Project Attributes

Attributes are managed inline with the project form (not as separate CRUD).

### API Endpoints

```
POST   /api/dashboard/projects/{uuid}/attributes              → Add attribute
PUT    /api/dashboard/projects/{uuid}/attributes/{attrUuid}   → Update
DELETE /api/dashboard/projects/{uuid}/attributes/{attrUuid}   → Delete
PATCH  /api/dashboard/projects/{uuid}/attributes/reorder      → Reorder
```

---

## UI Components (Vue)

### Project List View

- Table with columns: Image, Title, Location, Status, Published, Actions
- Search input
- Filter dropdowns (Category, Status, Location, Published)
- "Show deleted" toggle (admin only)
- Drag-drop row reordering
- Pagination

### Project Form

- Title input
- Slug input (auto-generated from title, editable)
- Description (rich text editor - TipTap or similar)
- Location dropdown
- Categories multi-select
- Statuses multi-select
- Publish toggle
- **Attributes section:**
  - List of label/value pairs
  - Add/remove buttons
  - Drag-drop reorder
- **Media section:**
  - Uppy upload zone
  - Grid of uploaded images
  - Mark one as teaser
  - Edit alt/caption inline
  - Drag-drop reorder
  - Delete with confirmation

---

## Business Rules

1. Slug must be unique and URL-safe
2. Auto-generate slug from title if not provided
3. Only published projects appear on frontend
4. Deleting a project soft-deletes it (recoverable)
5. Only admin can permanently delete or restore
6. At least one image should be marked as teaser
7. Categories and statuses are optional but recommended
