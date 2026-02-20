# Views Structure Consistency Plan (Projects + Office)

## Goal

Align folder and file organization in `resources/js/app/views/projects` and `resources/js/app/views/office` so both modules follow predictable naming and layout patterns.

This document defines **target structure**, **naming conventions**, and a **phased migration plan**.

---

## Current State Summary

### Projects

Current structure in `views/projects` mixes:
- route pages at root (`Index.vue`, `Show.vue`, `Images.vue`, `Text.vue`)
- feature pages under `web/`
- reusable pieces under `components/`

Observed consistency issues:
1. Naming collision: `Text.vue` page vs `components/Text.vue` section.
2. Mixed intent in `components/` (sections, layout, nav).
3. Repeated page shell (project nav + back button + title) across pages.
4. Inconsistent naming (`MetaData.vue` vs `Layout.vue`, `Settings.vue`).

### Office

Current structure in `views/office` is domain-first and mostly clean:
- one folder per entity (`awards`, `jobs`, `jury`, `network`, `talks`, `team`)
- mostly consistent `Index.vue` + `Form.vue`

Observed consistency issues:
1. `jobs` and `team` are custom implementations while others use shared section components.
2. Naming differs by entity intent (`Form.vue` vs `Show.vue` in team).
3. Team has nested `components/`, while other entities rely on shared UI components.

---

## Naming Conventions (Target)

1. Route-level files end with `Page.vue`.
   - Examples: `IndexPage.vue`, `DetailPage.vue`, `EditPage.vue`, `MetadataPage.vue`.
2. Reusable local pieces use semantic suffixes.
   - `*Card.vue`, `*Section.vue`, `*Layout.vue`, `*NavBar.vue`.
3. `Metadata` spelling is preferred over `MetaData`.
4. Keep one concern per folder level:
   - `pages/` for route pages
   - `sections/` for page sections
   - `layout/` for wrappers/shells
   - `navigation/` for local nav bars

---

## Proposed Target: Projects

```text
views/projects/
  pages/
    IndexPage.vue

  detail/
    DetailPage.vue
    sections/
      ImagesCard.vue
      TextCards.vue
      MasterDataCard.vue

  edit/
    layout/
      ProjectPageLayout.vue
    images/
      ImagesEditPage.vue
    text/
      TextEditPage.vue
    web/
      layout/
        WebPageLayout.vue
      pages/
        WebOverviewPage.vue
        MetadataPage.vue
        TeaserImagePage.vue
        SettingsPage.vue

  navigation/
    ProjectNavBar.vue
    WebNavBar.vue
```

### Notes

- Move shared shell from `components/WebLayout.vue` into `edit/web/layout/WebPageLayout.vue`.
- Add `edit/layout/ProjectPageLayout.vue` for shared non-web project header/nav shell.
- Rename local section components to avoid route-page naming collisions.

---

## Proposed Target: Office

```text
views/office/
  awards/
    pages/
      IndexPage.vue
      FormPage.vue

  jury/
    pages/
      IndexPage.vue
      FormPage.vue

  network/
    pages/
      IndexPage.vue
      FormPage.vue

  talks/
    pages/
      IndexPage.vue
      FormPage.vue

  jobs/
    pages/
      IndexPage.vue
      FormPage.vue

  team/
    pages/
      IndexPage.vue
      DetailPage.vue
    sections/
      TeamImageCard.vue
      TeamProfileCard.vue
      TeamBioCard.vue
```

### Notes

- Keep domain-first grouping (already good).
- Standardize page naming to `*Page.vue` across all office entities.
- Keep team-specific sections colocated under `team/sections/`.

---

## Shared Consistency Rules Across Projects + Office

1. **Domain-first at top level** (`projects`, `office`).
2. **Pages vs sections vs layout clearly separated**.
3. **No ambiguous base names** (`Text.vue`, `Layout.vue`) where context can collide.
4. **Shared page shell extraction where repeated 2+ times**.
5. **Router imports should mirror folder intent** (e.g. `.../pages/...Page.vue`).

---

## Phased Migration Plan (Safe)

### Phase 1: Naming only
- Rename ambiguous files (especially `Text.vue` collisions, `MetaData.vue` -> `MetadataPage.vue`).
- Keep behavior unchanged.

### Phase 2: Local folder reshuffle
- Introduce `pages/`, `sections/`, `layout/`, `navigation/` folders.
- Move files with import updates only.

### Phase 3: Shared layout extraction
- Introduce `ProjectPageLayout` for non-web project edit pages.
- Migrate pages incrementally.

### Phase 4: Router cleanup
- Update route imports to new paths/names.
- Keep route names and URLs stable unless explicitly required.

---

## Non-Goals

- No URL/path changes are required by this plan.
- No functional behavior changes are required by this plan.
- This is a structural consistency refactor only.
