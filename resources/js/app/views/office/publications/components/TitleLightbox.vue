<script setup>
import { ref } from 'vue'
import { useFormErrors } from '@/composables/useFormErrors'
import { useLightbox } from '@/composables/useLightbox'
import publicationsApi from '@/api/publications'
import Lightbox from '@/components/ui/lightbox/Lightbox.vue'
import Button from '@/components/ui/form/Button.vue'
import Input from '@/components/ui/form/Input.vue'

const emit = defineEmits(['saved', 'created'])

const title = ref('')
const subtitle = ref('')
const publicationRef = ref(null)

const { get, clear, submit } = useFormErrors()
const { show, open: openLightbox, close } = useLightbox(() => {
	clear()
})

function open(publication = null) {
	publicationRef.value = publication
	title.value = publication?.title ?? ''
	subtitle.value = publication?.subtitle ?? ''
	openLightbox()
}

async function save() {
	if (publicationRef.value) {
		const ok = await submit(() =>
			publicationsApi.update(publicationRef.value.uuid, {
				title: title.value,
				subtitle: subtitle.value || null,
			})
		)
		if (ok) {
			close()
			emit('saved')
		}
	} else {
		const ok = await submit(() => publicationsApi.store({
			title: title.value,
			subtitle: subtitle.value || null,
		}))
		if (ok) {
			close()
			emit('created')
		}
	}
}

defineExpose({ open })
</script>

<template>
	<Lightbox :open="show" :title="publicationRef ? 'Publikation bearbeiten' : 'Neue Publikation'" @close="close">
		<form @submit.prevent="save" class="px-20">
			<Input v-model="title" :error="get('title')" placeholder="Titel" class="form-input form-input--lg" @focus="clear('title')" />
			<Input v-model="subtitle" :error="get('subtitle')" placeholder="Untertitel" class="form-input form-input--lg mt-10" @focus="clear('subtitle')" />
			<div class="flex gap-20 mt-20">
				<Button type="submit" class="flex justify-center">{{ publicationRef ? 'Speichern' : 'Erstellen' }}</Button>
				<Button @click="close" class="flex justify-center">Abbrechen</Button>
			</div>
		</form>
	</Lightbox>
</template>
