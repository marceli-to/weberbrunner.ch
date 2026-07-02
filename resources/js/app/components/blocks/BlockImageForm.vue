<script setup>
import { ref, computed } from 'vue'
import MediaCard from '@/components/media/MediaCard.vue'
import AddButton from '@/components/media/AddButton.vue'
import MediaPickerDrawer from '@/components/media/MediaPickerDrawer.vue'
import MediaUploader from '@/components/media/MediaUploader.vue'
import { useCan } from '@/composables/useCan'

const { canUpdate, canDelete, canPublish } = useCan()

const props = defineProps({
	block: { type: Object, required: true },
	mediaPool: { type: Array, default: () => [] },
	allowUpload: { type: Boolean, default: false },
	allowPick: { type: Boolean, default: true },
})

const emit = defineEmits(['select-media', 'upload-media', 'remove-media', 'toggle-publish', 'edit-media'])

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
		<div v-if="image || allowPick" class="grid grid-cols-4 gap-20">
			<MediaCard
				v-if="image"
				:item="image"
				:publishable="canPublish"
				:deletable="canDelete"
				:editable="canUpdate"
				show-filename
				@delete="$emit('remove-media', image.uuid)"
				@toggle-publish="$emit('toggle-publish', image)"
				@edit="$emit('edit-media', $event)" />
			<AddButton v-else-if="allowPick" @click="openDrawer" />
		</div>

		<div v-if="allowUpload && !image" class="mt-20">
			<MediaUploader @uploaded="$emit('upload-media', $event)" />
		</div>

		<MediaPickerDrawer
			v-if="allowPick"
			:open="drawerOpen"
			:items="mediaPool"
			v-model="selectedUuid"
			submit-label="Übernehmen"
			cancel-label="Abbrechen"
			@close="drawerOpen = false"
			@submit="onDrawerSubmit" />
	</div>
</template>
