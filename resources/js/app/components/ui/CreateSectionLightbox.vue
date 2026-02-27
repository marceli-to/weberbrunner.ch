<script setup>
import { ref } from 'vue'
import { useFormErrors } from '@/composables/useFormErrors'
import { useLightbox } from '@/composables/useLightbox'
import Lightbox from '@/components/ui/lightbox/Lightbox.vue'
import Button from '@/components/ui/form/Button.vue'
import Input from '@/components/ui/form/Input.vue'

const props = defineProps({
	storeFn: Function,
	updateFn: Function,
	lightboxTitle: {
		type: String,
		default: 'Neue Kategorie',
	},
})

const emit = defineEmits(['stored'])

const title = ref('')
const editingItem = ref(null)
const { get, clear, submit } = useFormErrors()
const { show, open: openLightbox, close } = useLightbox(() => {
	title.value = ''
	editingItem.value = null
	clear()
})

function open() {
	editingItem.value = null
	openLightbox()
}

function edit(item) {
	openLightbox()
	editingItem.value = item
	title.value = item.title
}

async function store() {
	const fn = editingItem.value && props.updateFn
		? () => props.updateFn(editingItem.value.uuid, title.value)
		: () => props.storeFn(title.value)
	const ok = await submit(fn)
	if (ok) {
		close()
		emit('stored')
	}
}

defineExpose({ open, edit })
</script>

<template>
	<Lightbox :open="show" :title="lightboxTitle" @close="close" :closeable="false">
		<form @submit.prevent="store" class="px-20">
			<Input v-model="title" :error="get('title')" placeholder="Bezeichnung" class="form-input form-input--lg" @focus="clear('title')" />
			<div class="flex gap-20 mt-20">
				<Button type="submit" class="flex justify-center">Speichern</Button>
				<Button @click="close" class="flex justify-center">Abbrechen</Button>
			</div>
		</form>
	</Lightbox>
</template>
