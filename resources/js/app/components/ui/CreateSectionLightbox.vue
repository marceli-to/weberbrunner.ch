<script setup>
import { ref } from 'vue'
import { useFormErrors } from '@/composables/useFormErrors'
import { useLightbox } from '@/composables/useLightbox'
import Lightbox from '@/components/ui/lightbox/Lightbox.vue'
import Button from '@/components/ui/form/Button.vue'
import Input from '@/components/ui/form/Input.vue'

const props = defineProps({
	storeFn: Function,
})

const emit = defineEmits(['stored'])

const title = ref('')
const { get, clear, submit } = useFormErrors()
const { show, open, close } = useLightbox(() => {
	title.value = ''
	clear()
})

async function store() {
	const ok = await submit(() => props.storeFn(title.value))
	if (ok) {
		close()
		emit('stored')
	}
}

defineExpose({ open })
</script>

<template>
	<Lightbox :open="show" title="Neue Kategorie" @close="close" :closeable="false">
		<form @submit.prevent="store" class="px-20">
			<Input v-model="title" :error="get('title')" placeholder="Bezeichnung" class="form-input form-input--lg" @focus="clear('title')" />
			<div class="flex gap-20 mt-24">
				<Button type="submit" class="flex justify-center">Speichern</Button>
				<Button @click="close" class="flex justify-center">Abbrechen</Button>
			</div>
		</form>
	</Lightbox>
</template>
