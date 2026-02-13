# Nested Drag & Drop (vuedraggable)

Sortable parent groups with sortable child entries. Entries can be moved between groups.

## Dependencies

- `vuedraggable` v4 (already installed)
- `Burger` icon component as drag handle

## Pattern

```vue
<script setup>
import { ref } from 'vue'
import draggable from 'vuedraggable'
import Burger from '@/components/icons/Burger.vue'

const categories = ref([
	{
		id: 1,
		title: 'Category A',
		entries: [
			{ id: 1, title: 'Entry 1' },
			{ id: 2, title: 'Entry 2' },
		],
	},
	{
		id: 2,
		title: 'Category B',
		entries: [
			{ id: 3, title: 'Entry 3' },
		],
	},
])
</script>

<template>
	<!-- Outer: reorder categories -->
	<draggable
		v-model="categories"
		item-key="id"
		handle=".category-handle"
		ghost-class="ghost-category"
		drag-class="bg-white"
		animation="150"
	>
		<template #item="{ element: category }">
			<div class="mb-20">

				<div class="flex items-center gap-12 py-12 border-b border-black">
					<Burger class="w-14 cursor-grab category-handle" />
					<span class="text-sm font-semibold text-black">{{ category.title }}</span>
				</div>

				<!-- Inner: reorder entries, group="entries" allows cross-category moves -->
				<draggable
					v-model="category.entries"
					item-key="id"
					group="entries"
					handle=".entry-handle"
					ghost-class="ghost-entry"
					drag-class="bg-white"
					animation="150"
					class="ml-24"
				>
					<template #item="{ element: entry }">
						<div class="flex items-center gap-12 py-10 border-b border-silver">
							<Burger class="w-12 cursor-grab entry-handle text-gray" />
							<span class="text-sm text-black">{{ entry.title }}</span>
						</div>
					</template>
				</draggable>

			</div>
		</template>
	</draggable>
</template>

<style scoped>
:deep(.ghost-category) {
	background-color: #f0f0f0;
	border-radius: 2px;
}
:deep(.ghost-category) * {
	opacity: 0;
}
:deep(.ghost-entry) {
	background-color: #f0f0f0;
	border-radius: 2px;
}
:deep(.ghost-entry) * {
	opacity: 0;
}
</style>
```

## Key props

| Prop | Purpose |
|------|---------|
| `handle` | CSS selector restricting drag to the handle element |
| `ghost-class` | Class applied to the placeholder in the original position |
| `drag-class` | Class applied to the element being dragged |
| `group` | Shared name allows entries to move between lists |
| `animation` | Transition duration in ms for reorder animation |
| `item-key` | Unique key for Vue list rendering |

## Notes

- Each draggable level needs its own `handle` class (`.category-handle` vs `.entry-handle`) to prevent conflicts
- `group` is only needed on the inner draggable — it creates a shared pool so entries can cross between categories
- Ghost styles use `:deep()` because vuedraggable applies classes outside the scoped component
