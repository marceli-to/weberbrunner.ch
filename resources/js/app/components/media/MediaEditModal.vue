<script setup>
import { ref, computed, watch } from 'vue'
import Lightbox from '@/components/ui/lightbox/Lightbox.vue'
import Input from '@/components/ui/form/Input.vue'
import Button from '@/components/ui/form/Button.vue'

const props = defineProps({
	media: { type: Object, default: null },
})

const emit = defineEmits(['close', 'save'])

const open = computed(() => !!props.media)

const form = ref({
	original_name: '',
	caption: '',
	alt: '',
	credits: '',
})

watch(() => props.media, (val) => {
	if (val) {
		form.value.original_name = val.original_name || ''
		form.value.caption = val.caption || ''
		form.value.alt = val.alt || ''
		form.value.credits = val.credits || ''
	}
}, { immediate: true })

function handleSave() {
	emit('save', {
		uuid: props.media.uuid,
		data: {
			caption: form.value.caption,
			alt: form.value.alt,
			credits: form.value.credits,
		},
	})
}
</script>

<template>
	<Lightbox :open="open" closeable @close="emit('close')">

		<!-- Image preview -->
		<div class="flex items-center justify-center mb-20">
			<img
				v-if="media"
				:src="media.preview_url"
				:alt="media.alt || ''"
				class="max-h-[60vh] max-w-full object-contain"
			/>
		</div>

		<!-- Form fields -->
		<div class="grid grid-cols-[auto_1fr] gap-x-20 gap-y-12 items-center mb-20">
			<label class="text-sm font-semibold text-black">Dateiname</label>
			<Input v-model="form.original_name" disabled />

			<label class="text-sm font-semibold text-black">Bildlegende</label>
			<Input v-model="form.caption" />

			<label class="text-sm font-semibold text-black">Alt-Text</label>
			<Input v-model="form.alt" />

			<label class="text-sm font-semibold text-black">Credit</label>
			<Input v-model="form.credits" />
		</div>

		<!-- Actions -->
		<div class="grid grid-cols-2 gap-20">
			<Button type="button" @click="handleSave">Speichern</Button>
			<Button type="button" @click="emit('close')">Abbrechen</Button>
		</div>

	</Lightbox>
</template>
