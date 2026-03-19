<script setup>
import { ref } from 'vue'
import { useFormErrors } from '@/composables/useFormErrors'
import { useLightbox } from '@/composables/useLightbox'
import publicationsApi from '@/api/publications'
import Lightbox from '@/components/ui/lightbox/Lightbox.vue'
import Button from '@/components/ui/form/Button.vue'
import Input from '@/components/ui/form/Input.vue'

const emit = defineEmits(['saved'])

const key = ref('')
const value = ref('')
const publicationRef = ref(null)
const attributeRef = ref(null)

const { get, clear, submit } = useFormErrors()
const { show, open: openLightbox, close } = useLightbox(() => {
	clear()
})

function open(publication, attribute = null) {
	publicationRef.value = publication
	attributeRef.value = attribute
	key.value = attribute?.key ?? ''
	value.value = attribute?.value ?? ''
	openLightbox()
}

async function save() {
	const ok = await submit(() => {
		if (attributeRef.value) {
			return publicationsApi.attributes.update(publicationRef.value.uuid, attributeRef.value.uuid, {
				key: key.value,
				value: value.value,
			})
		}
		return publicationsApi.attributes.store(publicationRef.value.uuid, {
			key: key.value,
			value: value.value,
		})
	})
	if (ok) {
		close()
		emit('saved')
	}
}

defineExpose({ open })
</script>

<template>
	<Lightbox :open="show" title="Metadaten bearbeiten" @close="close">
		<form @submit.prevent="save" class="px-20">
			<Input v-model="key" :error="get('key')" placeholder="Bezeichnung" class="form-input form-input--lg" @focus="clear('key')" />
			<Input v-model="value" :error="get('value')" placeholder="Wert" class="form-input form-input--lg mt-10" @focus="clear('value')" />
			<div class="flex gap-20 mt-20">
				<Button type="submit" class="flex justify-center">Speichern</Button>
				<Button @click="close" class="flex justify-center">Abbrechen</Button>
			</div>
		</form>
	</Lightbox>
</template>
