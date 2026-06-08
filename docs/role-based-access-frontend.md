# Role-Based Access Control — Frontend Plan

Status: **Documentation only.** No code has been changed. This document maps every Vue component that needs a client-side permission guard and describes the proposed `useCan()` composable. Implementation is pending confirmation of the permission matrix.

## Background

The backend already enforces all permissions via Laravel policies (see the permission tier table in `CLAUDE.md`). The Vue frontend is currently **permission-blind**: every create/edit/delete/reorder control is rendered for all authenticated users. A viewer can click a delete button, but the API returns `403 Forbidden`. The security boundary holds; the UX is wrong.

The authenticated user's `role` is **already available on the client** — shipped via `UserResource` (`app/Http/Resources/UserResource.php:18`) and stored in `authStore.user.role` (`resources/js/app/stores/auth.js`). No backend changes are required to read the role on the frontend.

## Permission Matrix (to confirm)

| Action               | Admin | Editor | Viewer |
| -------------------- | ----- | ------ | ------ |
| view / viewAny       | yes   | yes    | yes    |
| create / update      | yes   | yes    | no     |
| delete / restore     | yes   | no     | no     |
| reorder              | yes   | yes    | no     |
| upload               | yes   | yes    | no     |

**Special cases:**

- **Users section** — viewAny/view restricted to **admin + editor only** (viewers cannot see it at all). Hide the whole section, not just its buttons.
- **Activity log** — **admin only**.
- **Settings** (statuses / categories / masterdata) — backend treats create/update/delete as the standard tiers, but these are admin-oriented. Confirm whether editors should see/edit Settings at all, or whether the whole section is admin-only.

> `reorder` and `upload` are not separate policy abilities on the backend — they map to `update`/`create`. They are listed separately here only because they are distinct UI controls (drag handles, uploaders) that each need a guard.

## The `useCan()` Composable

A single composable, read from the auth store, is the source of truth for all guards. Proposed location: `resources/js/app/composables/useCan.js`.

```js
import { computed } from 'vue'
import { useAuthStore } from '@/stores/auth'

export function useCan() {
	const auth = useAuthStore()

	const role = computed(() => auth.user?.role ?? null)

	const isAdmin  = computed(() => role.value === 'admin')
	const isEditor = computed(() => role.value === 'editor')
	const isViewer = computed(() => role.value === 'viewer')

	// Coarse, role-based abilities mirroring the backend policy tiers.
	const canCreate  = computed(() => isAdmin.value || isEditor.value)
	const canUpdate  = computed(() => isAdmin.value || isEditor.value)
	const canReorder = computed(() => isAdmin.value || isEditor.value)
	const canUpload  = computed(() => isAdmin.value || isEditor.value)
	const canDelete  = computed(() => isAdmin.value)
	const canRestore = computed(() => isAdmin.value)

	// Section visibility.
	const canViewUsers    = computed(() => isAdmin.value || isEditor.value)
	const canViewActivity = computed(() => isAdmin.value)
	const canViewSettings = computed(() => isAdmin.value) // confirm: admin-only?

	return {
		role,
		isAdmin, isEditor, isViewer,
		canCreate, canUpdate, canDelete, canRestore, canReorder, canUpload,
		canViewUsers, canViewActivity, canViewSettings,
	}
}
```

### Usage pattern

```vue
<script setup>
import { useCan } from '@/composables/useCan'
const { canCreate, canDelete } = useCan()
</script>

<template>
	<Button v-if="canCreate" @click="createLightbox.open()">Add</Button>
	<IconButton v-if="canDelete" @delete="destroy(item)" />
</template>
```

### Design notes

- **Coarse role checks vs. per-resource `can`.** `UserResource` also ships a per-record `can: { update, delete }` array, but it is computed per-user-row, not a global capability map. For the current uniform matrix, gating on `role` via `useCan()` is sufficient and simpler. If permissions ever diverge per record, individual rows can additionally read a `can` prop.
- **Reusable components stay dumb.** Components like `EntryRow`, `DraggableEntryRow`, `MediaCard` emit events and expose visibility props (`deletable`, `editable`, `publishable`). The **parent** decides visibility by binding `useCan()` flags to those props. This keeps one guard policy and avoids embedding role logic in leaf components.
- **Defense in depth, not security.** Frontend guards are UX only. The backend policies remain the real boundary.

## Guard Inventory

### Infrastructure (must change first)

| File | Change |
| ---- | ------ |
| `resources/js/app/composables/useCan.js` | **New** — the composable above |
| `resources/js/app/stores/auth.js` | Already holds `user` incl. `role`. No change needed; verify `role` is populated after `fetchUser()` |
| `resources/js/app/router/index.js` | Add route guards (`beforeEnter`) for Settings/Activity/Users and create/edit routes |
| `resources/js/app/components/layout/AppSidebar.vue` | Hide nav items for Settings (admin), Activity (admin), Users (admin+editor) |

### High-Leverage Reusable Components

Guarding these (via parent-bound props) covers the majority of action controls across the app.

| Component | Controls to guard | Actions | Used by |
| --------- | ----------------- | ------- | ------- |
| `components/ui/EntryRow.vue` | edit (pencil), publish toggle (eye), default toggle (star) | update | Contacts, Jobs, Publications attrs, Masterdata |
| `components/ui/DraggableEntryRow.vue` | drag handle, edit, publish toggle, delete | reorder, update, delete | Jobs, Settings, SectionGroupedIndex |
| `components/media/MediaCard.vue` | drag, publish toggle, delete, edit (props: `draggable`, `publishable`, `deletable`, `editable`) | reorder, update, delete | Project images, Publications, WebLayout, blocks, team photos |
| `components/media/MediaUploader.vue` | upload | create | Team image, Publications teaser/OG, Contacts, Project images, blocks |
| `components/ui/SectionTitleForm.vue` | submit (create/update) | create, update | Settings, SectionGroupedIndex, PageBlocks |
| `components/ui/SectionGroupedIndex.vue` | create category/entry, section drag, edit/delete section, entry drag, edit/publish/delete entry | create, update, delete, reorder | Talks, Jury, Awards |
| `components/ui/SectionEntryForm.vue` | submit | create, update | Talks, Jury, Awards forms |
| `components/blocks/PageBlocks.vue` | add block, drag, delete, edit title, upload/remove/edit media, link add/save/delete/toggle/reorder | create, update, delete, reorder | Network, Intro, Arbeitsweisen |
| `components/blocks/BlockCard.vue` | delete, edit title (prop: `editable`) | update, delete | PageBlocks, Publications, Project web layout |
| `components/blocks/BlockImageForm.vue` | media card delete/edit/publish, add, upload | create, update, delete, reorder | PageBlocks, Project web layout |

### Page-Specific Components

| File | Controls (line) | Actions |
| ---- | --------------- | ------- |
| `views/projects/IndexPage.vue` | create (46) | create |
| `views/projects/ShowPage.vue` | title edit (41) | update |
| `views/projects/web/WebLayoutPage.vue` | slider media delete/edit/publish (101–104) | update, delete |
| `views/projects/TextEditPage.vue` | save short desc (87), save desc (107) | update |
| `views/projects/ImagesEditPage.vue` | delete/edit (131), upload (136), reorder+attach (142) | create, update, delete, reorder |
| `views/projects/MasterdataEditPage.vue` | submit (77) | update |
| `views/projects/web/SettingsPage.vue` | publish toggle (30), status (44–49), category (63–66) | update |
| `views/projects/web/MetadataPage.vue` | save desc (35), delete/edit OG (55) | update, delete |
| `views/projects/web/TeaserImagePage.vue` | delete/edit teaser (27) | update, delete |
| `views/office/team/IndexPage.vue` | create (46) | create |
| `views/office/team/components/TeamProfile.vue` | edit (89), delete (147), save (145) | update, delete |
| `views/office/team/components/TeamImage.vue` | delete (51), upload (56) | create, delete |
| `views/office/team/components/TeamBio.vue` | edit (106), remove row (139), add row (148), save (149) | create, update, delete |
| `views/office/publications/IndexPage.vue` | create (69), drag (94), delete (107), reorder (87) | create, delete, reorder |
| `views/office/publications/ShowPage.vue` | upload (45), delete (59), reorder slider (64), download add/delete (72–82+) | create, delete, reorder |
| `views/office/publications/SettingsPage.vue` | publish toggle (30) | update |
| `views/office/publications/MetadataPage.vue` | save desc (63), delete OG (82), upload OG (84) | create, update, delete |
| `views/office/publications/TeaserImagePage.vue` | delete (39), upload (41) | create, delete |
| `views/office/contacts/IndexPage.vue` | edit (83), publish toggle (84), delete (87), create (93) | create, update, delete |
| `views/office/contacts/FormPage.vue` | submit (114), delete image (157), upload (161) | create, update, delete |
| `views/office/jobs/IndexPage.vue` | edit (100), publish toggle (101), delete (102), reorder (94), create (108) | create, update, delete, reorder |
| `views/office/jobs/FormPage.vue` | submit (72) | create, update |
| `views/landing/IndexPage.vue` | save text (151), add project (195), reorder (190), delete (190) | create, update, delete, reorder |
| `views/settings/IndexPage.vue` | statuses/categories/masterdata: create/edit/delete/reorder + toggle-default (see lines 182–333) | create, update, delete, reorder |

> Talks / Jury / Awards index and form pages delegate entirely to `SectionGroupedIndex` and `SectionEntryForm` (covered above). Network / Intro / Arbeitsweisen index pages delegate to `PageBlocks` (covered above). Guarding the reusable components covers these.

### Route Guards (`router/index.js`)

Add `beforeEnter` guards so non-permitted users are redirected rather than reaching a dead page:

- **Admin-only:** `settings.*`, activity (if a route exists), and any Users management routes.
- **Admin + editor only:** Users viewing routes.
- **Create / edit routes** (redirect viewers): `*.create`, `*.edit` for contacts, jobs, talks, jury, awards, projects (images/text/masterdata edit), publications, team detail edits.

## Implementation Order (when approved)

1. Add `useCan.js`; confirm `authStore.user.role` is populated.
2. Wire the high-leverage reusable components (parents bind `useCan()` flags to existing visibility props). This is the highest coverage-per-change step.
3. Sweep page-specific controls with `v-if`.
4. Add sidebar nav filtering.
5. Add router `beforeEnter` guards.
6. Manual QA per role (admin / editor / viewer).

## Open Questions

1. Is the permission matrix above exactly correct, including reorder/upload mapping to create/update?
2. Should **editors** see the **Settings** section (statuses/categories/masterdata) at all, or is it admin-only?
3. Is there a frontend **Users** management section/route today, or only the Profile page? (Affects whether the admin+editor Users guard has a target.)
4. For viewers hitting a guarded route directly — redirect to dashboard, or show a "no access" page?
