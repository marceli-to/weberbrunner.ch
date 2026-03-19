<script setup>
import { ref, computed, watch } from 'vue'
import Document from '@/components/icons/Document.vue'
import Lightbox from '@/components/ui/lightbox/Lightbox.vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import Label from '@/components/ui/form/Label.vue'
import Input from '@/components/ui/form/Input.vue'
import Button from '@/components/ui/form/Button.vue'

const props = defineProps({
	media: { type: Object, default: null },
})

const emit = defineEmits(['close', 'save'])

const open = computed(() => !!props.media)

const form = ref({
	caption: '',
	alt: '',
	credits: '',
})

watch(() => props.media, (val) => {
	if (val) {
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

		<!-- Preview -->
		<div class="flex items-center justify-center pt-20 py-60 border-b-thin border-black">
			<template v-if="media?.preview_url">
				<img
					:src="media.preview_url"
					:alt="media.alt || ''"
					class="max-h-[30vh] max-w-full object-contain"
				/>
			</template>
			<div v-else-if="media" class="flex flex-col items-center gap-5 text-gray-400">
				<Document class="w-64 h-auto" />
				<span class="text-sm">{{ media.original_name }}</span>
			</div>
		</div>

		<!-- Form fields -->
		<Grid :cols="8" class="gap-y-10 my-40 px-20">
			<Span class="col-span-2 flex items-center">
				<Label>Dateiname</Label>
			</Span>
			<Span class="col-span-6">
				<Input :model-value="media?.original_name || ''" disabled />
			</Span>

			<template v-if="media?.is_image !== false">
				<Span class="col-span-2 flex items-center">
					<Label>Bildlegende</Label>
				</Span>
				<Span class="col-span-6">
					<Input v-model="form.caption" />
				</Span>

				<Span class="col-span-2 flex items-center">
					<Label>Alt-Text</Label>
				</Span>
				<Span class="col-span-6">
					<Input v-model="form.alt" />
				</Span>
			</template>

			<Span class="col-span-2 flex items-center">
				<Label>Credit</Label>
			</Span>
			<Span class="col-span-6">
				<Input v-model="form.credits" />
			</Span>
		</Grid>

		<!-- Actions -->
		<Grid :cols="8" class="gap-y-10 px-20">
			<Span class="col-span-6 col-start-3">
				<Button type="button" @click="handleSave" class="px-10">Speichern</Button>
			</Span>
			<Span class="col-span-6 col-start-3">
				<Button type="button" @click="emit('close')" class="px-10">Abbrechen</Button>
			</Span>
		</Grid>

	</Lightbox>
</template>
