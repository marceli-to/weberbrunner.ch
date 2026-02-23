# Dynamic Content Blocks for Project Detail Page

## Context

Each project's detail page has fixed elements (title, slideshow, master data, description). Users need the ability to add dynamic content blocks below the fixed content. Block types: **text**, **slider** (1:n images), **single image**, **links** (1:n, internal/external). Blocks are unlimited per type and fully reorderable.

## Architecture: Parent table + child table for links

- `project_blocks` — single table with `type` column (text|slider|image|links), `title`, `content` (text blocks only), `sort_order`
- `project_block_links` — child table for link items within a links block
- Media attaches to `ProjectBlock` via existing polymorphic `Media` model (morph target)

This follows the existing patterns (`ProjectAttribute`, `ProjectLink`) rather than using JSON.

---

## 1. Database Migrations

### `create_project_blocks_table`
```
id, uuid, project_id (FK cascade), type (string), title (nullable), content (text, nullable), sort_order, timestamps
```

### `create_project_block_links_table`
```
id, uuid, project_block_id (FK cascade), title (nullable), url (nullable), link_type (default 'external'), linked_project_id (FK nullable, nullOnDelete), sort_order, timestamps
```

## 2. Models

### `ProjectBlock` — `app/Models/ProjectBlock.php`
- Traits: `HasFactory, HasUuid, Sortable`
- Fillable: `project_id, type, title, content, sort_order`
- Relationships: `project()` belongsTo, `links()` hasMany ordered, `media()` morphMany ordered
- Pattern follows: `ProjectAttribute`

### `ProjectBlockLink` — `app/Models/ProjectBlockLink.php`
- Traits: `HasFactory, HasUuid, Sortable`
- Fillable: `project_block_id, title, url, link_type, linked_project_id, sort_order`
- Relationships: `block()` belongsTo, `linkedProject()` belongsTo

### Update `Project` model
- Add `blocks()` hasMany relationship ordered by sort_order

## 3. Actions

### `App\Actions\ProjectBlock\`
- `StoreAction` — `$project->blocks()->create($data)`
- `UpdateAction` — `$block->update($data)`
- `DeleteAction` — deletes block media from disk (via `DeleteMediaAction`), then deletes block (links cascade via FK)
- `ReorderAction` — bulk update sort_order

### `App\Actions\ProjectBlockLink\`
- `StoreAction` — `$block->links()->create($data)`
- `UpdateAction` — `$link->update($data)`
- `DeleteAction` — `$link->delete()`
- `ReorderAction` — bulk update sort_order

## 4. Controllers & Routes

### `ProjectBlockController` — nested under `projects/{project}/blocks`
| Method | Route | Action |
|--------|-------|--------|
| GET `/` | index | List blocks with media + links |
| POST `/` | store | Create block |
| PATCH `/reorder` | reorder | Reorder blocks |
| PUT `/{block}` | update | Update block |
| DELETE `/{block}` | destroy | Delete block + media |

### `ProjectBlockLinkController` — nested under `projects/{project}/blocks/{block}/links`
| Method | Route | Action |
|--------|-------|--------|
| POST `/` | store | Add link |
| PATCH `/reorder` | reorder | Reorder links |
| PUT `/{link}` | update | Update link |
| DELETE `/{link}` | destroy | Delete link |

### Media attachment
- `POST projects/{project}/blocks/{block}/media` — reuses existing `AttachAction`

## 5. Form Requests

### `ProjectBlock/`
- `StoreProjectBlockRequest` — type (required, in:text,slider,image,links), title (nullable), content (nullable)
- `UpdateProjectBlockRequest` — title (nullable), content (nullable)
- `ReorderProjectBlockRequest` — items array with id + sort_order

### `ProjectBlockLink/`
- `StoreProjectBlockLinkRequest` — title, url, link_type (required, in:internal,external), linked_project_id (nullable, exists:projects)
- `UpdateProjectBlockLinkRequest` — same rules
- `ReorderProjectBlockLinkRequest` — items array

All requests: German error messages, authorize via `can('update', $this->route('project'))`.

## 6. API Resources

### `ProjectBlockResource`
- Fields: id, uuid, type, title, content, sort_order, media (whenLoaded), links (whenLoaded), timestamps

### `ProjectBlockLinkResource`
- Fields: id, uuid, title, url, link_type, linked_project_id, linked_project (whenLoaded), sort_order, timestamps

### Update `ProjectResource`
- Add `blocks` with whenLoaded

### Update `ProjectController` eager-loads
- Add `blocks.media`, `blocks.links.linkedProject` to all relevant load calls

## 7. Vue Dashboard UI

### API module — `resources/js/app/api/projectBlocks.js`
- Standard CRUD + reorder for blocks
- Nested CRUD + reorder for links
- Media attach endpoint

### Components structure
```
views/projects/components/
  ProjectBlocks.vue              — Main container on ShowPage
  blocks/
    BlockList.vue                — Draggable list (vuedraggable)
    BlockCard.vue                — Per-block card with type icon, title, edit/delete
    BlockAddMenu.vue             — Lightbox to pick block type (4 options)
    BlockTextForm.vue            — Title + Editor (TipTap) for text content
    BlockSliderForm.vue          — Title + MediaUploader + draggable MediaCards
    BlockImageForm.vue           — Title + single MediaCard with MediaPickerDrawer
    BlockLinksForm.vue           — Title + draggable link rows
    BlockLinkRow.vue             — Single link: title, type toggle, url/project picker
```

### ShowPage integration
Add `<ProjectBlocks>` below existing `ProjectMasterData` in `ShowPage.vue`.

### Reused existing components
- `CollapsibleHeader` + `useCollapsed` — for block expand/collapse
- `MediaPickerDrawer` — image/slider media selection
- `MediaUploader` — direct upload to block
- `MediaCard` — image display within blocks
- `vuedraggable` — block and link reordering
- `useFormErrors` — form validation
- `useToast` / `useConfirm` — feedback and confirmations
- `Lightbox` — block type picker
- `Input`, `Editor`, `Select`, `Button` — form elements

## 8. Implementation Order

| Phase | Steps |
|-------|-------|
| **1. Backend DB** | Migrations → run → models → Project relationship |
| **2. Backend blocks CRUD** | Requests → Actions → Resource → Controller → Routes |
| **3. Backend links CRUD** | Requests → Actions → Resource → Controller → Routes |
| **4. Backend media** | Block media controller → route → verify AttachAction works |
| **5. Frontend API** | API module + update ProjectResource eager-loads |
| **6. Frontend block list** | BlockCard → BlockAddMenu → BlockList → ProjectBlocks → ShowPage |
| **7. Frontend block forms** | TextForm → ImageForm → SliderForm → LinkRow → LinksForm |
| **8. Integration** | End-to-end testing of all block types |

## Verification

1. Run `php artisan migrate` — tables created
2. API test: POST a block of each type, verify response
3. API test: CRUD links within a links block
4. API test: Attach media to slider/image blocks
5. API test: Reorder blocks, verify sort_order updates
6. Dashboard: Add blocks from ShowPage, verify inline editing
7. Dashboard: Drag-reorder blocks, verify persistence
8. Dashboard: Delete block, verify media files removed from disk

## Open Questions / TODOs

1. **Media deletion cascading to blocks:** If an image is deleted from the media table (e.g. via the global media manager), but is still referenced by a `slider` or `image` block — what should happen? Options: (a) prevent deletion if media is attached to a block, (b) allow deletion and let the block gracefully handle the missing image, (c) cascade-remove the block itself. Needs a decision before launch.
