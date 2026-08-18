# weberbrunner.ch

Laravel 12 headless CMS with Vue 3 (Inertia) dashboard. API-only backend, Blade frontend.

## Tech Stack

- **Backend:** Laravel 12, PHP 8.4, SQLite
- **Frontend:** Vue 3 + Inertia.js + Tailwind CSS 4
- **Auth:** Laravel Breeze (session-based)
- **Activity logging:** spatie/laravel-activitylog

## Code Style

- **Indentation:** 1 tab everywhere (Blade, PHP, CSS, JS, Vue)
- **No docstrings/comments** unless logic is non-obvious

## Architecture

### Models & Relationships

```
Location ──hasMany──> Project, TeamMember, Job
Project  ──belongsTo──> Location
         ──hasMany──> ProjectAttribute
         ──morphMany──> Media
         ──belongsToMany──> Category, Status (pivot tables)
TeamMember ──belongsTo──> Location
           ──hasMany──> TeamMemberBio
           ──morphMany──> Media
NetworkEntry ──morphMany──> Media
Section(type=award) ──hasMany──> Award
Section(type=jury)  ──hasMany──> Jury
Section(type=talk)  ──hasMany──> Talk
Award ──belongsTo──> Section
Jury  ──belongsTo──> Section
Talk  ──belongsTo──> Section
```

Full ER diagram: `docs/er-diagram.md`

### File Structure

```
app/Actions/{Domain}/{Verb}Action.php       — Business logic (Store, Update, Delete, Reorder)
app/Http/Controllers/Api/{Domain}Controller.php
app/Http/Requests/{Domain}/{Verb}{Domain}Request.php
app/Http/Resources/{Domain}Resource.php
app/Models/{Domain}.php
app/Policies/{Domain}Policy.php
database/factories/{Domain}Factory.php
```

### Conventions

- **Actions** omit the domain prefix (namespace provides context): `App\Actions\Media\DeleteAction`
- **Controllers** alias actions for readability: `use App\Actions\Media\DeleteAction as DeleteMediaAction;`
- **Actions are instantiated inline**, not injected: `(new DeleteMediaAction)->execute(...)`
- **Morph fields** (`mediable_type`, `mediable_id`) are NOT in `$fillable` — create through the relationship instead
- **UUID routing:** All models use `HasUuid` trait, route binding uses UUID not integer ID
- **Sortable:** All models use `Sortable` trait, auto-assigns `sort_order = max + 1` on create

### Traits

| Trait | Purpose |
|-------|---------|
| `HasUuid` | Auto-generates UUID, sets `getRouteKeyName()` to `uuid` |
| `Sortable` | Auto-increments `sort_order` on creation |

### Permission Tiers (Policies)

| Action | Admin | Editor | Viewer |
|--------|-------|--------|--------|
| viewAny / view | yes | yes | yes |
| create / update | yes | yes | no |
| delete / restore | yes | no | no |

Exceptions:
- `UserPolicy` restricts viewAny/view to admin+editor only.
- `MediaPolicy` lets editors delete media attached to a TeamMember (portraits), so they can replace them.

### API Routes

All under `POST /api/dashboard/` with `['web', 'auth']` middleware.

Standard CRUD pattern (most resources):
```
GET    /{resource}              → index
POST   /{resource}              → store
PATCH  /{resource}/reorder      → reorder
GET    /{resource}/{uuid}       → show
PUT    /{resource}/{uuid}       → update
DELETE /{resource}/{uuid}       → destroy
PATCH  /{resource}/{uuid}/restore → restore
```

Nested resources:
- `projects/{project}/attributes` — ProjectAttribute CRUD
- `team/{teamMember}/cv` — TeamMemberBio CRUD

Special:
- `media/upload` (POST), `media/{media}/teaser` (PATCH)
- `sections?type=award|jury|talk` — filtered index
- `activity` — admin-only activity log

### Polymorphic Media

Media attaches to models via `mediable` morph. Morph targets: Project, TeamMember, NetworkEntry. Each parent has `media()` (all) and `teaser()` (is_teaser=true) relationships.

### Section Grouping

Awards, Jury, and Talks are grouped by Section records scoped via `type` column (`award`, `jury`, `talk`). Each type has independent sections with their own sort order. Index endpoints join on `sections.sort_order` for grouped ordering.

### Soft Deletes

All models except Media, ProjectAttribute, TeamMemberBio.

### Activity Logging

All models use Spatie `LogsActivity` with `logAll()->logOnlyDirty()`.

<skills_system priority="1">

## Available Skills

<!-- SKILLS_TABLE_START -->
<usage>
When users ask you to perform tasks, check if any of the available skills below can help complete the task more effectively. Skills provide specialized capabilities and domain knowledge.

How to use skills:
- Invoke: `skillkit read <skill-name>` or `npx skillkit read <skill-name>`
- The skill content will load with detailed instructions on how to complete the task
- Base directory provided in output for resolving bundled resources (references/, scripts/, assets/)

Usage notes:
- Only use skills listed in <available_skills> below
- Do not invoke a skill that is already loaded in your context
- Each skill invocation is stateless
</usage>

<available_skills>

<skill>
<name>ascii</name>
<description>No description available</description>
<location>project</location>
</skill>

<skill>
<name>class</name>
<description>No description available</description>
<location>project</location>
</skill>

<skill>
<name>er</name>
<description>No description available</description>
<location>project</location>
</skill>

<skill>
<name>flowchart</name>
<description>No description available</description>
<location>project</location>
</skill>

<skill>
<name>sequence</name>
<description>No description available</description>
<location>project</location>
</skill>

<skill>
<name>skillkit-45f018b8-6e2a-4b5b-9c2e-44e1a76d95cb</name>
<description>No description available</description>
<location>project</location>
</skill>

<skill>
<name>themes</name>
<description>No description available</description>
<location>project</location>
</skill>

</available_skills>
<!-- SKILLS_TABLE_END -->

</skills_system>
