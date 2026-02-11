# Dialog Component

## Overview

A native `<dialog>`-based modal system with two components and a composable:

- **`AppDialog.vue`** — Base dialog (generic, slotted)
- **`ConfirmDialog.vue`** — Pre-built confirmation dialog
- **`useConfirm.js`** — Composable for promise-based confirms

---

## Files

```
resources/js/app/
├── components/ui/dialog/
│   ├── AppDialog.vue
│   └── ConfirmDialog.vue
└── composables/
    └── useConfirm.js
```

---

## AppDialog.vue

**Path:** `resources/js/app/components/ui/dialog/AppDialog.vue`

Base dialog component using the native HTML `<dialog>` element.

### Props

| Prop   | Type    | Default | Description                  |
|--------|---------|---------|------------------------------|
| `open` | Boolean | `false` | Controls dialog visibility   |
| `title`| String  | `null`  | Optional header title        |
| `size` | String  | `'sm'`  | Width: `sm` (360), `md` (480), `lg` (640) |

### Events

| Event   | Description              |
|---------|--------------------------|
| `close` | Emitted when dialog closes (X button, backdrop click, or ESC) |

### Slots

| Slot     | Description                        |
|----------|------------------------------------|
| default  | Dialog body content                |
| `header` | Custom header (replaces title)     |
| `footer` | Optional footer area               |

### Code

```vue
<script setup>
import { ref, watch } from 'vue'
import Cross from '@/components/icons/Cross.vue'

const props = defineProps({
	open: { type: Boolean, default: false },
	title: { type: String, default: null },
	size: { type: String, default: 'sm' },
})

const emit = defineEmits(['close'])

const dialogRef = ref(null)

const sizes = {
	sm: 'max-w-360',
	md: 'max-w-480',
	lg: 'max-w-640',
}

watch(() => props.open, (val) => {
	if (val) {
		dialogRef.value?.showModal()
	} else {
		dialogRef.value?.close()
	}
})

function onClose() {
	emit('close')
}
</script>

<template>
	<dialog
		ref="dialogRef"
		class="p-0 m-auto bg-white border border-silver shadow-xl backdrop:bg-black/40 w-full"
		:class="sizes[size]"
		@close="onClose"
		@click.self="onClose"
	>
		<div class="p-24">
			<!-- Header -->
			<div v-if="title || $slots.header" class="flex items-start justify-between mb-20">
				<slot name="header">
					<h2 class="text-sm font-semibold text-black">{{ title }}</h2>
				</slot>
				<button
					type="button"
					class="w-20 h-20 flex items-center justify-center text-black cursor-pointer -mt-2 -mr-2"
					@click="onClose"
				>
					<Cross class="w-10 h-10" />
				</button>
			</div>

			<!-- Body -->
			<div>
				<slot />
			</div>

			<!-- Footer -->
			<div v-if="$slots.footer" class="mt-24">
				<slot name="footer" />
			</div>
		</div>
	</dialog>
</template>
```

### Usage

```vue
<AppDialog :open="isOpen" title="Edit Item" size="md" @close="isOpen = false">
	<p>Dialog body content here.</p>

	<template #footer>
		<button
			type="button"
			class="border border-black text-black text-sm font-semibold px-16 py-8"
			@click="isOpen = false"
		>
			Close
		</button>
	</template>
</AppDialog>
```

---

## ConfirmDialog.vue

**Path:** `resources/js/app/components/ui/dialog/ConfirmDialog.vue`

Standalone confirmation dialog with variant support. Uses full-width action rows with icons (Cross for cancel, Checkmark for confirm).

### Props

| Prop           | Type    | Default              | Description                                   |
|----------------|---------|----------------------|-----------------------------------------------|
| `open`         | Boolean | `false`              | Controls visibility                           |
| `message`      | String  | `'Sind Sie sicher?'` | Confirmation message                          |
| `confirmLabel` | String  | `'Bestätigen'`       | Confirm button text                           |
| `cancelLabel`  | String  | `'Abbrechen'`        | Cancel button text                            |
| `variant`      | String  | `'default'`          | Visual variant: `default` (white) or `danger` (red) |

### Events

| Event     | Description                |
|-----------|----------------------------|
| `confirm` | User clicked confirm       |
| `cancel`  | User clicked cancel or closed dialog |

### Variants

- **`default`** — White background, black text, black/white action buttons
- **`danger`** — Red (`#dc0000`) background, white text, red/white action buttons

---

## useConfirm.js Composable

**Path:** `resources/js/app/composables/useConfirm.js`

Promise-based composable for triggering confirmation dialogs from anywhere. Uses shared reactive state — a single `ConfirmDialog` instance in the layout handles all confirms.

### Code

```js
import { reactive } from 'vue'

const state = reactive({
	open: false,
	message: 'Sind Sie sicher?',
	confirmLabel: 'Bestätigen',
	cancelLabel: 'Abbrechen',
	variant: 'default',
	resolve: null,
})

export function useConfirm() {
	function confirm(options = {}) {
		return new Promise((resolve) => {
			Object.assign(state, {
				open: true,
				message: options.message ?? 'Sind Sie sicher?',
				confirmLabel: options.confirmLabel ?? 'Bestätigen',
				cancelLabel: options.cancelLabel ?? 'Abbrechen',
				variant: options.variant ?? 'default',
				resolve,
			})
		})
	}

	function onConfirm() {
		state.open = false
		state.resolve?.(true)
	}

	function onCancel() {
		state.open = false
		state.resolve?.(false)
	}

	return { state, confirm, onConfirm, onCancel }
}
```

### Usage (in any component)

```js
import { useConfirm } from '@/composables/useConfirm'

const { confirm } = useConfirm()

async function handleDelete() {
	const ok = await confirm({
		message: 'Möchten Sie diesen Eintrag wirklich löschen?',
		confirmLabel: 'Löschen',
		variant: 'danger',
	})

	if (ok) {
		// proceed with deletion
	}
}
```

### Options

| Option         | Type    | Default              | Description                              |
|----------------|---------|----------------------|------------------------------------------|
| `message`      | String  | `'Sind Sie sicher?'` | Message body                             |
| `confirmLabel` | String  | `'Bestätigen'`       | Confirm button text                      |
| `cancelLabel`  | String  | `'Abbrechen'`        | Cancel button text                       |
| `variant`      | String  | `'default'`          | Visual variant: `default` or `danger`    |

---

## Layout Integration

**Path:** `resources/js/app/components/layout/AppLayout.vue`

A single `ConfirmDialog` is mounted in the root layout, wired to the shared `useConfirm` state:

```vue
<script setup>
import AppHeader from '@/components/layout/AppHeader.vue'
import AppSidebar from '@/components/layout/AppSidebar.vue'
import ToastContainer from '@/components/ui/toast/Container.vue'
import ConfirmDialog from '@/components/ui/dialog/ConfirmDialog.vue'
import { useConfirm } from '@/composables/useConfirm'

const { state: confirmState, onConfirm, onCancel } = useConfirm()
</script>

<template>
	<div class="min-h-screen flex flex-col">
		<AppHeader />
		<div class="bg-snow grid grid-cols-12 gap-x-20 flex-1">
			<AppSidebar />
			<main class="col-span-10 bg-snow relative pt-40">
				<ToastContainer />
				<slot />
			</main>
		</div>
		<ConfirmDialog
			:open="confirmState.open"
			:message="confirmState.message"
			:confirm-label="confirmState.confirmLabel"
			:cancel-label="confirmState.cancelLabel"
			:variant="confirmState.variant"
			@confirm="onConfirm"
			@cancel="onCancel"
		/>
	</div>
</template>
```

This means you never need to add `ConfirmDialog` to individual views — just call `confirm()` from the composable.

---

## Dependencies

- **Cross icon** (`@/components/icons/Cross.vue`) — close/cancel button icon (project-local SVG component)
- **Checkmark icon** (`@/components/icons/Checkmark.vue`) — confirm button icon (project-local SVG component)
- No external icon library or button component required — uses plain `<button>` elements with Tailwind classes
