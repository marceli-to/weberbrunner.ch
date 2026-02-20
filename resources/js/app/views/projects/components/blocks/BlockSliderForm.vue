<script setup>
import { ref, computed } from 'vue'
import draggable from 'vuedraggable'
import MediaCard from '@/components/media/MediaCard.vue'
import AddButton from '@/components/media/AddButton.vue'
import MediaPickerDrawer from '@/components/media/MediaPickerDrawer.vue'

const props = defineProps({
	block: { type: Object, required: true },
	projectMedia: { type: Array, default: () => [] },
})

const emit = defineEmits(['select-media', 'remove-media', 'reorder-media'])

const drawerOpen = ref(false)
const selectedUuids = ref([])

const images = computed({
	get: () => props.block.media || [],
	set: (value) => {
		const items = value.map((item, index) => ({
			id: item.id,
			sort_order: index,
		}))
		emit('reorder-media', items)
	},
})

function openDrawer() {
	selectedUuids.value = []
	drawerOpen.value = true
}

function onDrawerSubmit() {
	if (selectedUuids.value.length) {
		emit('select-media', selectedUuids.value)
	}
	drawerOpen.value = false
}

</script>

<template>
	<div class="flex flex-col gap-y-10 pt-10">
		<div class="grid grid-cols-3 gap-10">
			<draggable
				v-if="images.length"
				v-model="images"
				item-key="uuid"
				handle=".drag-handle"
				class="col-span-3 grid grid-cols-3 gap-10"
				ghost-class="opacity-30"
				animation="150">
				<template #item="{ element }">
					<MediaCard
						:item="element"
						:draggable="true"
						:deletable="true"
						compact
						variant="dark"
						@delete="$emit('remove-media', element.uuid)" />
				</template>
			</draggable>
			<AddButton @click="openDrawer" />
		</div>

		<MediaPickerDrawer
			:open="drawerOpen"
			:items="projectMedia"
			v-model="selectedUuids"
			:multiple="true"
			submit-label="Übernehmen"
			cancel-label="Abbrechen"
			@close="drawerOpen = false"
			@submit="onDrawerSubmit" />
	</div>
</template>
