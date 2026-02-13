# Database ER Diagram

```mermaid
erDiagram
    %% ==================
    %% CORE ENTITIES
    %% ==================

    User {
        bigint id PK
        uuid uuid UK
        string firstname
        string name
        string email UK
        string password
        enum role "admin|editor|viewer"
        string remember_token
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at
    }

    Location {
        bigint id PK
        uuid uuid UK
        string title
        string slug UK
        int sort_order
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at
    }

    %% ==================
    %% PROJECTS
    %% ==================

    Project {
        bigint id PK
        uuid uuid UK
        string title
        string slug UK
        text description
        bigint location_id FK
        boolean publish
        int sort_order
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at
    }

    Category {
        bigint id PK
        uuid uuid UK
        string title
        string slug UK
        int sort_order
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at
    }

    Status {
        bigint id PK
        uuid uuid UK
        string title
        string slug UK
        int sort_order
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at
    }

    ProjectAttribute {
        bigint id PK
        uuid uuid UK
        bigint project_id FK
        string label
        text value
        int sort_order
        timestamp created_at
        timestamp updated_at
    }

    category_project {
        bigint category_id FK
        bigint project_id FK
    }

    project_status {
        bigint project_id FK
        bigint status_id FK
    }

    %% ==================
    %% MEDIA (Polymorphic)
    %% ==================

    Media {
        bigint id PK
        uuid uuid UK
        string mediable_type
        bigint mediable_id
        string file
        string original_name
        string mime_type
        int size
        int width
        int height
        string alt
        string caption
        boolean is_teaser
        int sort_order
        timestamp created_at
        timestamp updated_at
    }

    %% ==================
    %% TEAM
    %% ==================

    TeamMember {
        bigint id PK
        uuid uuid UK
        string firstname
        string name
        string email
        string title
        int since
        bigint location_id FK
        string slug UK
        boolean publish
        int sort_order
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at
    }

    TeamMemberBio {
        bigint id PK
        uuid uuid UK
        bigint team_member_id FK
        string period
        text description
        int sort_order
        timestamp created_at
        timestamp updated_at
    }

    %% ==================
    %% CONTENT
    %% ==================

    Job {
        bigint id PK
        uuid uuid UK
        string title
        text description
        bigint location_id FK
        string contact_email
        boolean publish
        int sort_order
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at
    }

    Talk {
        bigint id PK
        uuid uuid UK
        string title
        string event
        string location
        date date
        string link
        boolean publish
        int sort_order
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at
    }

    Award {
        bigint id PK
        uuid uuid UK
        string title
        text description
        int year
        bigint project_id FK "nullable"
        string link
        boolean publish
        int sort_order
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at
    }

    Jury {
        bigint id PK
        uuid uuid UK
        string title
        text description
        int year
        string link
        boolean publish
        int sort_order
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at
    }

    NetworkEntry {
        bigint id PK
        uuid uuid UK
        string title
        text description
        string category
        string link
        boolean publish
        int sort_order
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at
    }

    %% ==================
    %% RELATIONSHIPS
    %% ==================

    %% Location relationships
    Location ||--o{ Project : "has many"
    Location ||--o{ TeamMember : "has many"
    Location ||--o{ Job : "has many"

    %% Project relationships
    Project ||--o{ ProjectAttribute : "has many"
    Project ||--o{ category_project : "has many"
    Project ||--o{ project_status : "has many"
    Category ||--o{ category_project : "has many"
    Status ||--o{ project_status : "has many"

    %% Team relationships
    TeamMember ||--o{ TeamMemberBio : "has many"

    %% Award to Project (optional)
    Project ||--o{ Award : "has many (optional)"

    %% Polymorphic Media relationships (shown as dashed conceptually)
    Project ||--o{ Media : "morphMany"
    TeamMember ||--o{ Media : "morphMany"
    NetworkEntry ||--o{ Media : "morphMany"
```

## Relationship Legend

| Relationship | Type | Description |
|--------------|------|-------------|
| Location → Project | One-to-Many | Each project belongs to one location |
| Location → TeamMember | One-to-Many | Each team member belongs to one location |
| Location → Job | One-to-Many | Each job belongs to one location |
| Project → ProjectAttribute | One-to-Many | Projects have dynamic key-value attributes |
| Project ↔ Category | Many-to-Many | Via `category_project` pivot |
| Project ↔ Status | Many-to-Many | Via `project_status` pivot |
| Project → Media | Polymorphic | Projects can have multiple images |
| TeamMember → TeamMemberBio | One-to-Many | CV entries for each member |
| TeamMember → Media | Polymorphic | Portrait image |
| NetworkEntry → Media | Polymorphic | Logo image |
| Project → Award | One-to-Many (optional) | Awards can link to a project |

## Notes

- All entities have `uuid` for external API exposure
- Integer `id` used internally for foreign keys and relations
- `Media` is polymorphic via `mediable_type` and `mediable_id`
- Soft deletes (`deleted_at`) on most entities for trash/restore
- `sort_order` on all sortable entities for drag-drop ordering
