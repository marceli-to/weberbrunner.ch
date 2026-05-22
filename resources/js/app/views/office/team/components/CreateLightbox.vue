<script setup>
import { ref } from 'vue'
import { useFormErrors } from '@/composables/useFormErrors'
import { useLightbox } from '@/composables/useLightbox'
import teamApi from '@/api/team'
import Lightbox from '@/components/ui/lightbox/Lightbox.vue'
import Button from '@/components/ui/form/Button.vue'
import Input from '@/components/ui/form/Input.vue'

const emit = defineEmits(['created'])

const firstname = ref('')
const name = ref('')
const email = ref('')

const { get, clear, submit } = useFormErrors()
const { show, open: openLightbox, close } = useLightbox(() => {
	clear()
	firstname.value = ''
	name.value = ''
	email.value = ''
})

function open() {
	openLightbox()
}

async function save() {
	let created = null
	const ok = await submit(async () => {
		const response = await teamApi.store({
			firstname: firstname.value,
			name: name.value,
			email: email.value,
		})
		created = response.data.data
		return response
	})
	if (ok) {
		close()
		emit('created', created)
	}
}

defineExpose({ open })
</script>

<template>
	<Lightbox :open="show" title="Neues Teammitglied" @close="close">
		<form @submit.prevent="save" class="px-20">
			<Input v-model="firstname" :error="get('firstname')" placeholder="Vorname" class="form-input form-input--lg" @focus="clear('firstname')" />
			<Input v-model="name" :error="get('name')" placeholder="Nachname" class="form-input form-input--lg mt-10" @focus="clear('name')" />
			<Input v-model="email" :error="get('email')" placeholder="E-Mail-Adresse" class="form-input form-input--lg mt-10" @focus="clear('email')" />
			<div class="flex gap-20 mt-20">
				<Button type="submit" class="flex justify-center">Erstellen</Button>
				<Button @click="close" class="flex justify-center">Abbrechen</Button>
			</div>
		</form>
	</Lightbox>
</template>
