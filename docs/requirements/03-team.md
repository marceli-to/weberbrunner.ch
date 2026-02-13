# Team Module

## Overview

Team members with profile information, CV entries, and optional portrait image. Currently hardcoded in `Livewire/Team.php` - will be migrated to database.

---

## Model

### TeamMember

| Field | Type | Description |
|-------|------|-------------|
| id | bigint | Primary key (internal) |
| uuid | uuid | Unique, public identifier |
| firstname | string | First name |
| name | string | Last name |
| email | string | Nullable |
| title | string | Job title/role (e.g., "Architekt BSA SIA") |
| since | year | Year joined (e.g., 2015) |
| location_id | foreignId | References `locations` table |
| slug | string | URL-friendly, unique |
| publish | boolean | Default: true |
| sort_order | integer | Display order |
| created_at | timestamp | |
| updated_at | timestamp | |
| deleted_at | timestamp | Soft delete |

**Relationships:**
- `hasMany` → TeamMemberBio
- `morphMany` → Media (portrait image)
- `belongsTo` → Location

### TeamMemberBio

CV entries for detailed profile pages.

| Field | Type | Description |
|-------|------|-------------|
| id | bigint | Primary key (internal) |
| uuid | uuid | Unique, public identifier |
| team_member_id | foreignId | References `team_members` |
| period | string | Time period (e.g., "2015 – heute", "2010 – 2015") |
| description | text | What they did during this period |
| sort_order | integer | Display order |
| created_at | timestamp | |
| updated_at | timestamp | |

---

## Routes

### Web (Blade views)

```
GET    /dashboard/team               → Index (list view)
GET    /dashboard/team/create        → Create form
GET    /dashboard/team/{uuid}/edit   → Edit form
```

### API

```
GET    /api/dashboard/team           → List with pagination/filters
POST   /api/dashboard/team           → Store new member
GET    /api/dashboard/team/{uuid}    → Get single member
PUT    /api/dashboard/team/{uuid}    → Update member
DELETE /api/dashboard/team/{uuid}    → Soft delete
POST   /api/dashboard/team/{uuid}/restore → Restore deleted
PATCH  /api/dashboard/team/reorder   → Update sort order (bulk)
```

---

## API Endpoints

### List Team Members

**GET** `/api/dashboard/team`

**Query Parameters:**
| Param | Type | Description |
|-------|------|-------------|
| page | int | Page number |
| per_page | int | Items per page (default: 20) |
| search | string | Search in firstname/name |
| location | uuid | Filter by location UUID |
| publish | bool | Filter by publish status |
| trashed | bool | Include soft-deleted |

**Response:**
```json
{
  "data": [
    {
      "uuid": "550e8400-e29b-41d4-a716-446655440000",
      "firstname": "Anna",
      "name": "Müller",
      "email": "anna.mueller@weberbrunner.ch",
      "title": "Architektin ETH BSA SIA",
      "since": 2015,
      "slug": "anna-mueller",
      "publish": true,
      "location": {
        "uuid": "6ba7b810-9dad-11d1-80b4-00c04fd430c8",
        "title": "Zürich"
      },
      "portrait": {
        "uuid": "6ba7b811-9dad-11d1-80b4-00c04fd430c8",
        "url": "/img/team/anna-mueller.jpg"
      },
      "sort_order": 1
    }
  ],
  "meta": { ... }
}
```

### Get Team Member

**GET** `/api/dashboard/team/{uuid}`

**Response:**
```json
{
  "data": {
    "uuid": "550e8400-e29b-41d4-a716-446655440000",
    "firstname": "Anna",
    "name": "Müller",
    "email": "anna.mueller@weberbrunner.ch",
    "title": "Architektin ETH BSA SIA",
    "since": 2015,
    "slug": "anna-mueller",
    "publish": true,
    "sort_order": 1,
    "location": { "uuid": "6ba7b810-...", "title": "Zürich" },
    "cv": [
      {
        "uuid": "...",
        "period": "2015 – heute",
        "description": "Partnerin bei weberbrunner architekten",
        "sort_order": 1
      },
      {
        "uuid": "...",
        "period": "2010 – 2015",
        "description": "Projektleiterin bei XYZ Architekten",
        "sort_order": 2
      }
    ],
    "portrait": {
      "uuid": "...",
      "file": "team/anna-mueller.jpg",
      "alt": "Anna Müller"
    }
  }
}
```

### Store Team Member

**POST** `/api/dashboard/team`

**Request Body:**
```json
{
  "firstname": "Max",
  "name": "Muster",
  "email": "max.muster@weberbrunner.ch",
  "title": "Architekt ETH",
  "since": 2020,
  "slug": "max-muster",
  "location_uuid": "6ba7b810-9dad-11d1-80b4-00c04fd430c8",
  "publish": true,
  "cv": [
    { "period": "2020 – heute", "description": "Architekt bei weberbrunner" },
    { "period": "2015 – 2020", "description": "Studium ETH Zürich" }
  ]
}
```

**Validation:**
```php
[
    'firstname' => 'required|string|max:255',
    'name' => 'required|string|max:255',
    'email' => 'nullable|email|max:255',
    'title' => 'required|string|max:255',
    'since' => 'required|integer|min:1900|max:' . date('Y'),
    'slug' => 'required|string|max:255|unique:team_members,slug',
    'location_uuid' => 'required|exists:locations,uuid',
    'publish' => 'boolean',
    'cv' => 'array',
    'cv.*.period' => 'required|string|max:255',
    'cv.*.description' => 'required|string',
]
```

### Update Team Member

**PUT** `/api/dashboard/team/{uuid}`

Same structure as store, with slug uniqueness check excluding current record.

### Delete Team Member

**DELETE** `/api/dashboard/team/{uuid}`

Soft deletes the team member.

### Restore Team Member

**POST** `/api/dashboard/team/{uuid}/restore`

Admin only.

### Reorder Team Members

**PATCH** `/api/dashboard/team/reorder`

**Request Body:**
```json
{
  "items": [
    { "uuid": "550e8400-...", "sort_order": 1 },
    { "uuid": "6ba7b810-...", "sort_order": 2 }
  ]
}
```

---

## CV Entries

Managed inline with team member form (not separate CRUD).

### API Endpoints

```
POST   /api/dashboard/team/{uuid}/cv             → Add CV entry
PUT    /api/dashboard/team/{uuid}/cv/{cvUuid}    → Update entry
DELETE /api/dashboard/team/{uuid}/cv/{cvUuid}    → Delete entry
PATCH  /api/dashboard/team/{uuid}/cv/reorder     → Reorder entries
```

---

## UI Components (Vue)

### Team List View

- Grid or table with: Portrait, Name, Title, Location, Published, Actions
- Search input
- Location filter dropdown
- "Show deleted" toggle (admin only)
- Drag-drop reordering
- Pagination

### Team Member Form

- Firstname input
- Name input
- Email input
- Title input
- Since (year picker or number input)
- Slug input (auto-generated)
- Location dropdown
- Publish toggle
- **Portrait section:**
  - Single image upload (Uppy)
  - Preview with delete option
- **CV section:**
  - List of period/description pairs
  - Add/remove buttons
  - Drag-drop reorder
  - Rich text for description (optional)

---

## Data Migration

Migrate existing hardcoded team data from `Livewire/Team.php`:

```php
// Seeder: TeamMemberSeeder

$members = [
    ['firstname' => 'Anna', 'name' => 'Müller', 'title' => 'Architektin ETH BSA SIA', 'since' => 2015, 'email' => 'anna.mueller@weberbrunner.ch', 'location' => 'zuerich'],
    // ... 23 more members
];
```

---

## Business Rules

1. Slug must be unique (auto-generate from firstname + name if not provided)
2. Email is optional (not all members have public email)
3. Only published members appear on frontend
4. CV entries are optional
5. Portrait image is optional (show placeholder if missing)
6. Sort order determines display order on frontend
