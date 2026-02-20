<script setup>
import { ref, computed, watch } from 'vue'
import draggable from 'vuedraggable'
import Input from '@/components/ui/form/Input.vue'
import Button from '@/components/ui/form/Button.vue'
import MediaCard from '@/components/media/MediaCard.vue'
import AddButton from '@/components/media/AddButton.vue'
import MediaPickerDrawer from '@/components/media/MediaPickerDrawer.vue'

const props = defineProps({
	block: { type: Object, required: true },
	projectMedia: { type: Array, default: () => [] },
})

const emit = defineEmits(['save', 'select-media', 'remove-media', 'reorder-media'])

const title = ref(props.block.title || '')
const drawerOpen = ref(false)
const selectedUuids = ref([])

watch(() => props.block, (val) => {
	title.value = val.title || ''
})

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

function save() {
	emit('save', { title: title.value })
}
</script>

<template>
	<div class="flex flex-col gap-y-10 pt-10">
		<Input
			v-model="title"
			placeholder="Titel" />

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

		<div class="flex justify-end pt-5">
			<Button variant="primary" @click="save">Speichern</Button>
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
