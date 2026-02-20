<script setup>
import { ref, computed } from 'vue'
import MediaCard from '@/components/media/MediaCard.vue'
import AddButton from '@/components/media/AddButton.vue'
import MediaPickerDrawer from '@/components/media/MediaPickerDrawer.vue'
import Cross from '@/components/icons/Cross.vue'

const props = defineProps({
	block: { type: Object, required: true },
	projectMedia: { type: Array, default: () => [] },
})

const emit = defineEmits(['select-media', 'remove-media'])

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
	<div class="flex flex-col gap-y-10 pt-10">
		<div class="grid grid-cols-3 gap-10">
			<div v-if="image" class="relative">
				<MediaCard :item="image" compact />
				<button
					type="button"
					class="absolute top-5 right-5 cursor-pointer"
					@click="$emit('remove-media', image.uuid)">
					<Cross class="w-10" />
				</button>
			</div>
			<AddButton v-if="!image" @click="openDrawer" />
		</div>

		<MediaPickerDrawer
			:open="drawerOpen"
			:items="projectMedia"
			v-model="selectedUuid"
			submit-label="Übernehmen"
			cancel-label="Abbrechen"
			@close="drawerOpen = false"
			@submit="onDrawerSubmit" />
	</div>
</template>
