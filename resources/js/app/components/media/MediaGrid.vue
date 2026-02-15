<script setup>
import { computed } from 'vue'
import draggable from 'vuedraggable'
import Cross from '@/components/icons/Cross.vue'
import PencilCircle from '@/components/icons/PencilCircle.vue'
import Checkmark from '@/components/icons/Checkmark.vue'

const props = defineProps({
	items: { type: Array, default: () => [] },
})

const emit = defineEmits(['edit', 'delete', 'reorder', 'teaser'])

const dragItems = computed({
	get: () => props.items,
	set: (value) => emit('reorder', value),
})
</script>

<template>
	<draggable
		v-model="dragItems"
		item-key="uuid"
		class="grid grid-cols-2 lg:grid-cols-6 gap-20"
		ghost-class="opacity-30"
		animation="150"
	>
		<template #item="{ element }">
			<div class="relative group border-thin border-silver" :class="{ 'border-black': element.is_teaser }">
				<img
					:src="element.thumbnail_url"
					:alt="element.alt || ''"
					class="block w-full aspect-square object-cover"
				/>
				<div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-8">
					<button
						type="button"
						class="w-24 h-24 flex items-center justify-center bg-white text-black"
						title="Bearbeiten"
						@click="emit('edit', element)"
					>
						<PencilCircle class="w-12 h-12" />
					</button>
					<button
						type="button"
						class="w-24 h-24 flex items-center justify-center bg-white text-black"
						title="Teaser"
						@click="emit('teaser', element)"
					>
						<Checkmark class="w-10 h-10" />
					</button>
					<button
						type="button"
						class="w-24 h-24 flex items-center justify-center bg-white text-black"
						title="Löschen"
						@click="emit('delete', element)"
					>
						<Cross class="w-8 h-8" />
					</button>
				</div>
				<div
					v-if="element.is_teaser"
					class="absolute top-0 left-0 bg-black text-white text-[10px] font-semibold px-4 py-2 leading-none"
				>
					Teaser
				</div>
			</div>
		</template>
		<template #footer>
			<slot name="append" />
		</template>
	</draggable>
</template>
