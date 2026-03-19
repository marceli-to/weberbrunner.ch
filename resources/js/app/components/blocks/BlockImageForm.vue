<script setup>
import { ref, computed } from 'vue'
import MediaCard from '@/components/media/MediaCard.vue'
import AddButton from '@/components/media/AddButton.vue'
import MediaPickerDrawer from '@/components/media/MediaPickerDrawer.vue'

const props = defineProps({
	block: { type: Object, required: true },
	mediaPool: { type: Array, default: () => [] },
})

const emit = defineEmits(['select-media', 'remove-media', 'toggle-publish', 'edit-media'])

const drawerOpen = ref(false)
const selectedUuid = ref(null)

const image = computed(() => props.block.media?.[0] || null)

function openDrawer() {
	selectedUuid.value = null
	drawerOpen.value = true
}

function onDrawerSubmit() {
	if (selectedUuid.value) {
		emit('select-media', [selectedUuid.value])
	}
	drawerOpen.value = false
}
</script>

<template>
	<div class="pt-10">
		<div class="grid grid-cols-4 gap-20">
			<MediaCard
				v-if="image"
				:item="image"
				publishable
				deletable
				editable
				show-filename
				@delete="$emit('remove-media', image.uuid)"
				@toggle-publish="$emit('toggle-publish', image)"
				@edit="$emit('edit-media', $event)" />
			<AddButton v-else @click="openDrawer" />
		</div>

		<MediaPickerDrawer
			:open="drawerOpen"
			:items="mediaPool"
			v-model="selectedUuid"
			submit-label="Übernehmen"
			cancel-label="Abbrechen"
			@close="drawerOpen = false"
			@submit="onDrawerSubmit" />
	</div>
</template>
