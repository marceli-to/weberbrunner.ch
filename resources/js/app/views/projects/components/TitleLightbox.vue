<script setup>
import { ref } from 'vue'
import { useFormErrors } from '@/composables/useFormErrors'
import { useLightbox } from '@/composables/useLightbox'
import projectsApi from '@/api/projects'
import Lightbox from '@/components/ui/lightbox/Lightbox.vue'
import Button from '@/components/ui/form/Button.vue'
import Input from '@/components/ui/form/Input.vue'

const emit = defineEmits(['saved'])

const title = ref('')
const city = ref('')
const projectRef = ref(null)

const { get, clear, submit } = useFormErrors()
const { show, open: openLightbox, close } = useLightbox(() => {
	clear()
})

function open(project) {
	projectRef.value = project
	title.value = project.title
	city.value = project.city ?? ''
	openLightbox()
}

async function save() {
	const ok = await submit(() =>
		projectsApi.update(projectRef.value.uuid, {
			title: title.value,
			number: projectRef.value.number,
			city: city.value || null,
		})
	)
	if (ok) {
		close()
		emit('saved')
	}
}

defineExpose({ open })
</script>

<template>
	<Lightbox :open="show" title="Projekttitel bearbeiten" @close="close">
		<form @submit.prevent="save" class="px-20">
			<Input v-model="title" :error="get('title')" placeholder="Titel" class="form-input form-input--lg" @focus="clear('title')" />
			<Input v-model="city" :error="get('city')" placeholder="Ort" class="form-input form-input--lg mt-10" @focus="clear('city')" />
			<div class="flex gap-20 mt-20">
				<Button type="submit" class="flex justify-center">Speichern</Button>
				<Button @click="close" class="flex justify-center">Abbrechen</Button>
			</div>
		</form>
	</Lightbox>
</template>
