<script setup>
import { ref, computed, watch } from 'vue'
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
		<div class="flex items-center justify-center pt-20 py-60 border-b-thin border-black">
			<img
				v-if="media"
				:src="media.preview_url"
				:alt="media.alt || ''"
				class="max-h-[30vh] max-w-full object-contain"
			/>
		</div>

		<!-- Form fields -->
		<Grid :cols="8" class="gap-y-10 my-40 px-20">
			<Span class="col-span-2 flex items-center">
				<Label>Dateiname</Label>
			</Span>
			<Span class="col-span-6">
				<Input v-model="form.original_name" disabled />
			</Span>

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
        <Button type="button" @click="handleSave">Speichern</Button>
      </Span>
      <Span class="col-span-6 col-start-3">
        <Button type="button" @click="emit('close')">Abbrechen</Button>
      </Span>
    </Grid>

	</Lightbox>
</template>
