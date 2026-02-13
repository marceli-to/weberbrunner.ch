# Content Module

## Overview

Secondary content types currently hardcoded in Blade templates:
- Jobs (job postings)
- Talks (lectures/presentations)
- Awards (recognition/prizes)
- Jury (jury memberships)
- Network (partners/collaborators)

---

## Jobs

### Model: Job

| Field | Type | Description |
|-------|------|-------------|
| id | bigint | Primary key (internal) |
| uuid | uuid | Unique, public identifier |
| title | string | Job title (e.g., "Architekt:in") |
| description | text | Job description, rich text |
| location_id | foreignId | References `locations` |
| contact_email | string | Application email |
| publish | boolean | Default: false |
| sort_order | integer | Display order |
| created_at | timestamp | |
| updated_at | timestamp | |
| deleted_at | timestamp | Soft delete |

**Relationships:**
- `belongsTo` → Location

### Routes

```
GET    /api/dashboard/jobs           → List
POST   /api/dashboard/jobs           → Create
GET    /api/dashboard/jobs/{uuid}    → Get
PUT    /api/dashboard/jobs/{uuid}    → Update
DELETE /api/dashboard/jobs/{uuid}    → Soft delete
POST   /api/dashboard/jobs/{uuid}/restore → Restore
PATCH  /api/dashboard/jobs/reorder   → Reorder
```

### Validation

```php
[
    'title' => 'required|string|max:255',
    'description' => 'required|string',
    'location_uuid' => 'required|exists:locations,uuid',
    'contact_email' => 'required|email',
    'publish' => 'boolean',
]
```

### UI

- Table: Title, Location, Published, Actions
- Form: Title, Description (rich text), Location dropdown, Contact email, Publish toggle

---

## Talks

### Model: Talk

| Field | Type | Description |
|-------|------|-------------|
| id | bigint | Primary key (internal) |
| uuid | uuid | Unique, public identifier |
| title | string | Talk title |
| event | string | Nullable, event name |
| location | string | Nullable, venue/city |
| date | date | Talk date |
| link | string | Nullable, URL to video/slides |
| publish | boolean | Default: true |
| sort_order | integer | Display order |
| created_at | timestamp | |
| updated_at | timestamp | |
| deleted_at | timestamp | Soft delete |

**Notes:**
- `location` here is a free text field (venue), not related to `locations` table
- Grouped by year on frontend

### Routes

```
GET    /api/dashboard/talks           → List
POST   /api/dashboard/talks           → Create
GET    /api/dashboard/talks/{uuid}    → Get
PUT    /api/dashboard/talks/{uuid}    → Update
DELETE /api/dashboard/talks/{uuid}    → Soft delete
POST   /api/dashboard/talks/{uuid}/restore → Restore
PATCH  /api/dashboard/talks/reorder   → Reorder
```

### Validation

```php
[
    'title' => 'required|string|max:255',
    'event' => 'nullable|string|max:255',
    'location' => 'nullable|string|max:255',
    'date' => 'required|date',
    'link' => 'nullable|url',
    'publish' => 'boolean',
]
```

### UI

- Table: Title, Event, Date, Link (icon if present), Published, Actions
- Filter by year
- Form: Title, Event, Location (text), Date picker, Link input, Publish toggle

---

## Awards

### Model: Award

| Field | Type | Description |
|-------|------|-------------|
| id | bigint | Primary key (internal) |
| uuid | uuid | Unique, public identifier |
| title | string | Award name |
| description | text | Nullable, additional details |
| year | integer | Year received |
| project_id | foreignId | Nullable, linked project |
| link | string | Nullable, external URL |
| publish | boolean | Default: true |
| sort_order | integer | Display order |
| created_at | timestamp | |
| updated_at | timestamp | |
| deleted_at | timestamp | Soft delete |

**Relationships:**
- `belongsTo` → Project (optional)

### Routes

```
GET    /api/dashboard/awards           → List
POST   /api/dashboard/awards           → Create
GET    /api/dashboard/awards/{uuid}    → Get
PUT    /api/dashboard/awards/{uuid}    → Update
DELETE /api/dashboard/awards/{uuid}    → Soft delete
POST   /api/dashboard/awards/{uuid}/restore → Restore
PATCH  /api/dashboard/awards/reorder   → Reorder
```

### Validation

```php
[
    'title' => 'required|string|max:255',
    'description' => 'nullable|string',
    'year' => 'required|integer|min:1900|max:' . date('Y'),
    'project_uuid' => 'nullable|exists:projects,uuid',
    'link' => 'nullable|url',
    'publish' => 'boolean',
]
```

### UI

- Table: Title, Year, Project (link), Published, Actions
- Filter by year
- Form: Title, Description, Year, Project dropdown (searchable), Link, Publish toggle

---

## Jury

### Model: Jury

| Field | Type | Description |
|-------|------|-------------|
| id | bigint | Primary key (internal) |
| uuid | uuid | Unique, public identifier |
| title | string | Competition/role name |
| description | text | Nullable |
| year | integer | Year |
| link | string | Nullable, external URL |
| publish | boolean | Default: true |
| sort_order | integer | Display order |
| created_at | timestamp | |
| updated_at | timestamp | |
| deleted_at | timestamp | Soft delete |

### Routes

```
GET    /api/dashboard/jury           → List
POST   /api/dashboard/jury           → Create
GET    /api/dashboard/jury/{uuid}    → Get
PUT    /api/dashboard/jury/{uuid}    → Update
DELETE /api/dashboard/jury/{uuid}    → Soft delete
POST   /api/dashboard/jury/{uuid}/restore → Restore
PATCH  /api/dashboard/jury/reorder   → Reorder
```

### Validation

```php
[
    'title' => 'required|string|max:255',
    'description' => 'nullable|string',
    'year' => 'required|integer|min:1900|max:' . date('Y'),
    'link' => 'nullable|url',
    'publish' => 'boolean',
]
```

### UI

- Table: Title, Year, Published, Actions
- Filter by year
- Form: Title, Description, Year, Link, Publish toggle

---

## Network

Partners, collaborators, and related organizations.

### Model: NetworkEntry

| Field | Type | Description |
|-------|------|-------------|
| id | bigint | Primary key (internal) |
| uuid | uuid | Unique, public identifier |
| title | string | Partner/organization name |
| description | text | Nullable |
| category | string | Nullable, type of partner |
| link | string | Nullable, website URL |
| publish | boolean | Default: true |
| sort_order | integer | Display order |
| created_at | timestamp | |
| updated_at | timestamp | |
| deleted_at | timestamp | Soft delete |

**Relationships:**
- `morphMany` → Media (optional logo)

### Routes

```
GET    /api/dashboard/network           → List
POST   /api/dashboard/network           → Create
GET    /api/dashboard/network/{uuid}    → Get
PUT    /api/dashboard/network/{uuid}    → Update
DELETE /api/dashboard/network/{uuid}    → Soft delete
POST   /api/dashboard/network/{uuid}/restore → Restore
PATCH  /api/dashboard/network/reorder   → Reorder
```

### Validation

```php
[
    'title' => 'required|string|max:255',
    'description' => 'nullable|string',
    'category' => 'nullable|string|max:255',
    'link' => 'nullable|url',
    'publish' => 'boolean',
]
```

### UI

- Table: Logo, Title, Category, Link, Published, Actions
- Form: Title, Description, Category, Link, Logo upload, Publish toggle

---

## Dashboard Navigation

Group these under "Büro" section:

```
Dashboard
├── Projekte
│   ├── Alle Projekte
│   ├── Kategorien
│   └── Status
├── Büro
│   ├── Team
│   ├── Jobs
│   ├── Vorträge
│   ├── Auszeichnungen
│   ├── Jury
│   └── Netzwerk
├── Standorte
└── Benutzer (Admin only)
```

---

## Common Patterns

All content entities share:

1. **Soft deletes** - Recoverable deletion
2. **Sort order** - Drag-drop reordering
3. **Publish toggle** - Show/hide on frontend
4. **Activity logging** - Track changes (see `07-shared-features.md`)

### API Response Format

All list endpoints support:
- `?page=1&per_page=20` - Pagination
- `?search=keyword` - Search (where applicable)
- `?publish=true|false` - Filter by published
- `?trashed=true` - Include soft-deleted (admin only)

### Bulk Operations

For each entity, support:
- Bulk delete: `DELETE /api/dashboard/{entity}/bulk` with `{ "ids": [1, 2, 3] }`
- Bulk publish: `PATCH /api/dashboard/{entity}/bulk/publish` with `{ "ids": [1, 2, 3], "publish": true }`
