# Vue Codebase Audit

Date: 2026-03-19

## Overview

- **128** Vue components
- **12** composables
- **22** API modules
- **3** Pinia stores
- **37** icon components

---

## 1. Dead Code

### 1.1 Unused Icon Components (4 files)

| File | Path |
|------|------|
| `BlockDownload.vue` | `components/icons/BlockDownload.vue` |
| `Info.vue` | `components/icons/Info.vue` |
| `LogoWBA.vue` | `components/icons/LogoWBA.vue` |
| `LogoWBPA.vue` | `components/icons/LogoWBPA.vue` |

These are never imported anywhere. Safe to delete.

### 1.2 Unused Import — `GridOverlay` in AppLayout

**File:** `components/layout/AppLayout.vue:6`

`GridOverlay` is imported but only used in a commented-out line (`<!-- <GridOverlay /> -->`). The import and the comment can be removed.

### 1.3 `Select` component — `error` prop defined but display-only

**File:** `components/ui/form/Select.vue:6`

The `error` prop is defined and applies a `has-error` CSS class (line 17), so it's technically *used* — but unlike `Input` and `Textarea`, `Select` never shows the error message as placeholder text. This is inconsistent but not dead code. Low priority.

### 1.4 `PageTitle` — `slug` prop is functional but potentially unused at call sites

**File:** `components/ui/PageTitle.vue:6-9`

The `slug` prop renders a preview link. Verify that at least one call site actually passes `:slug`. If no call site does, the prop and the `LinkExternal` icon import are dead code.

---

## 2. Duplicated Code — Refactoring Candidates

### 2.1 Section Index Pages (Critical)

**Files:**
- `views/office/awards/IndexPage.vue`
- `views/office/jury/IndexPage.vue`
- `views/office/talks/IndexPage.vue`
- `views/office/network/IndexPage.vue`

These 4 files are **identical** except for the imported API module and 3 string props (`page-title`, `section-type`, `route-prefix`). Each is ~13 lines. They all delegate to `SectionGroupedIndex`.

**Suggestion:** Replace with a single dynamic route component that resolves the API and labels from the route name/params, or keep as-is — the duplication is tiny and each file is a clear, self-contained route entry point. This is a judgment call.

### 2.2 Section Form Pages (Critical)

**Files:**
- `views/office/awards/FormPage.vue`
- `views/office/jury/FormPage.vue`
- `views/office/talks/FormPage.vue`
- `views/office/network/FormPage.vue`

Same pattern: 4 identical files (~11 lines each) that differ only in the imported API and 2 string props. All delegate to `SectionEntryForm`.

**Suggestion:** Same as 2.1 — consolidate or keep as thin wrappers.

### 2.3 TitleLightbox — Projects vs Publications

**Files:**
- `views/projects/components/TitleLightbox.vue` (56 lines)
- `views/office/publications/components/TitleLightbox.vue` (66 lines)

Both follow the same pattern:
- Import `useFormErrors`, `useLightbox`, `Lightbox`, `Button`, `Input`
- Expose `open()` via `defineExpose`
- Render a `Lightbox` with a form containing Input fields + Save/Cancel buttons

**Differences:** Projects edits `title` + `city` via `projectsApi.update()`. Publications edits `title` + `subtitle` via `publicationsApi.update()` and also supports *create* mode.

**Suggestion:** Extract a generic `FormLightbox` component that accepts a field config and API callback. Both lightboxes would become thin wrappers around it. Estimated savings: ~40 lines.

### 2.4 Metadata Pages — Projects vs Publications

**Files:**
- `views/projects/web/MetadataPage.vue` (69 lines)
- `views/office/publications/MetadataPage.vue` (89 lines)

Both render:
1. A "Meta Description" section with `CollapsibleHeader` + `Card` + `Textarea` + save button
2. An "Open Graph Image" section with `CollapsibleHeader` + `MediaCard`/`AddButton`

**Differences:** Projects uses `useProjectMeta` composable + `MediaPickerDrawer`. Publications manually manages state with `ref`/`watch`/`computed` and uses `MediaUploader` instead.

**Suggestion:** Extract a shared `MetadataSection` component for the textarea-save pattern (used in both). The OG image sections differ enough (picker vs uploader) to keep separate, or unify behind a prop.

### 2.5 Teaser Image Pages — Projects vs Publications

**Files:**
- `views/projects/web/TeaserImagePage.vue` (41 lines)
- `views/office/publications/TeaserImagePage.vue` (46 lines)

Both render: `SectionTitle` + `Grid/Span` + conditional `MediaCard` or upload mechanism.

**Differences:** Projects uses `useProjectTeaser` composable + `MediaPickerDrawer`. Publications uses inline logic + `MediaUploader`.

**Suggestion:** Extract a `TeaserImageSection` component that accepts the teaser image and emits upload/remove events. The parent decides *how* media is selected.

### 2.6 Location-Grouped Index Pages — Jobs vs Contacts

**Files:**
- `views/office/jobs/IndexPage.vue` (124 lines)
- `views/office/contacts/IndexPage.vue` (110 lines)

Both implement:
- `fetch()` → grouped data by location
- `destroy()` with `useConfirm` dialog
- `toggle()` publish
- `CollapsibleHeader` per location group
- `NewEntryButton` per group

**Differences:** Jobs has drag-to-reorder via `vuedraggable` + `DraggableEntryRow`. Contacts uses plain `EntryRow` + manual `Cross` delete icon. Contacts only shows the add button when the group is empty.

**Suggestion:** Extract a `useLocationGroupedIndex(api, collapsedKey)` composable that provides `groups`, `fetch`, `destroy`, `toggle`. Template differences are significant enough that a shared component would be forced; a shared composable is the better fit. Estimated savings: ~25 lines of script logic.

### 2.7 Block Image/Slider Forms — Media Picker Logic

**Files:**
- `components/blocks/BlockImageForm.vue` (57 lines)
- `components/blocks/BlockSliderForm.vue` (82 lines)

Both implement identical drawer logic:
- `drawerOpen` ref + `selectedUuid(s)` ref
- `openDrawer()` resets selection and opens
- `onDrawerSubmit()` emits `select-media` and closes
- `MediaPickerDrawer` with same props/events

**Differences:** Image is single-select, Slider is multi-select with drag reorder.

**Suggestion:** Extract a `useMediaPicker(multiple)` composable returning `{ drawerOpen, selected, openDrawer, onSubmit }`. Estimated savings: ~15 lines per component.

### 2.8 Checkbox vs Radio Components

**Files:**
- `components/ui/form/Checkbox.vue` (25 lines)
- `components/ui/form/Radio.vue` (29 lines)

Nearly identical structure: hidden native input + icon + label. Different underlying input types and value handling.

**Suggestion:** Keep separate. The components are small, the differences are meaningful (boolean vs value matching), and merging would add conditional complexity that isn't worth it.

---

## 3. Component Structure Assessment

### 3.1 Well-Structured Patterns

- **Composables** are well-scoped and consistently used (`useToast`, `useConfirm`, `useFormErrors`, `useCollapsed`, `useLightbox`, `usePageLoader`)
- **API modules** have clean separation per domain (22 modules)
- **`SectionGroupedIndex`** and **`SectionEntryForm`** successfully abstract the section-based CRUD pattern
- **`DraggableEntryRow`** properly composes `EntryRow` rather than duplicating it
- **Media components** (`MediaGrid`, `MediaCard`, `MediaUploader`, `MediaPickerDrawer`) form a cohesive module
- **Block components** (`BlockCard`, `BlockSelector`, `BlockTextForm`, etc.) are well-organized
- **Form components** (`Input`, `Select`, `Textarea`, `Button`) are consistent and minimal

### 3.2 Architectural Observations

**Asymmetric media handling between Projects and Publications:**
Projects use dedicated composables (`useProjectTeaser`, `useProjectMeta`) with `MediaPickerDrawer` (pick from existing). Publications use inline logic with `MediaUploader` (upload new). This is the root cause of duplication in Metadata and Teaser pages. Unifying the media selection approach would eliminate most of this duplication.

**Thin route wrappers (Section pages):**
The 8 nearly-identical section wrappers (4 Index + 4 Form) are a trade-off. They're extremely thin (~12 lines) and serve as clean route entry points. Consolidating them saves files but adds routing complexity. This is acceptable as-is.

---

## 4. Summary & Priority

| # | Issue | Type | Priority | Est. Savings |
|---|-------|------|----------|--------------|
| 1 | Delete 4 unused icon files | Dead code | Quick win | 4 files |
| 2 | Remove `GridOverlay` import + comment | Dead code | Quick win | 2 lines |
| 3 | `useLocationGroupedIndex` composable | Duplication | Medium | ~25 lines |
| 4 | `useMediaPicker` composable | Duplication | Medium | ~30 lines |
| 5 | Generic `FormLightbox` component | Duplication | Medium | ~40 lines |
| 6 | Shared `MetadataSection` component | Duplication | Low | ~20 lines |
| 7 | Shared `TeaserImageSection` component | Duplication | Low | ~15 lines |
| 8 | Unify media selection approach (Picker vs Uploader) | Architecture | Low | Enables 5-7 |
| 9 | Consolidate 8 section route wrappers | Duplication | Optional | 4-8 files |
