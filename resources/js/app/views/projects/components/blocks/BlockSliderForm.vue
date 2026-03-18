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

const emit = defineEmits(['select-media', 'remove-media', 'reorder-media', 'toggle-publish', 'edit-media'])

const drawerOpen = ref(false)
const selectedUuids = ref([])

const images = computed(() => props.block.media || [])

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

function onReorder() {
	const items = images.value.map((item, index) => ({
		uuid: item.uuid,
		sort_order: index,
	}))
	emit('reorder-media', items)
}
</script>

<template>
	<div class="pt-10">
		<draggable
			v-if="images.length"
			:list="images"
			item-key="uuid"
			handle=".drag-handle"
			class="grid grid-cols-4 gap-20"
			ghost-class="opacity-30"
			animation="150"
			@end="onReorder">
			<template #item="{ element }">
				<MediaCard
					:item="element"
					draggable
					publishable
					deletable
					editable
					show-filename
					@delete="$emit('remove-media', element.uuid)"
					@toggle-publish="$emit('toggle-publish', element)"
					@edit="$emit('edit-media', $event)" />
			</template>
			<template #footer>
				<AddButton @click="openDrawer" />
			</template>
		</draggable>
		<div v-else class="grid grid-cols-4 gap-20">
			<AddButton @click="openDrawer" />
		</div>

		<MediaPickerDrawer
			:open="drawerOpen"
			:items="projectMedia"
			v-model="selectedUuids"
			multiple
			submit-label="Übernehmen"
			cancel-label="Abbrechen"
			@close="drawerOpen = false"
			@submit="onDrawerSubmit" />
	</div>
</template>
