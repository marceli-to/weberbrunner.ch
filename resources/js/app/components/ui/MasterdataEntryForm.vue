<script setup>
import { ref, computed } from 'vue'
import { useFormErrors } from '@/composables/useFormErrors'
import { useLightbox } from '@/composables/useLightbox'
import Lightbox from '@/components/ui/lightbox/Lightbox.vue'
import Button from '@/components/ui/form/Button.vue'
import Input from '@/components/ui/form/Input.vue'

const props = defineProps({
	storeFn: Function,
	updateFn: Function,
})

const emit = defineEmits(['stored', 'updated'])

const title = ref('')
const groupId = ref(null)
const editingItem = ref(null)
const { get, clear, submit } = useFormErrors()
const { show, open: openLightbox, close } = useLightbox(() => {
	title.value = ''
	groupId.value = null
	editingItem.value = null
	clear()
})

const lightboxTitle = computed(() =>
	editingItem.value ? 'Eintrag bearbeiten' : 'Neuer Eintrag'
)

function open(masterdataGroupId) {
	openLightbox()
	groupId.value = masterdataGroupId
}

function edit(entry, masterdataGroupId) {
	openLightbox()
	editingItem.value = entry
	title.value = entry.title
	groupId.value = masterdataGroupId
}

async function store() {
	const data = {
		title: title.value,
		masterdata_group_id: groupId.value,
	}
	const isUpdate = !!(editingItem.value && props.updateFn)
	const fn = isUpdate
		? () => props.updateFn(editingItem.value.uuid, data)
		: () => props.storeFn(data)
	const ok = await submit(fn)
	if (ok) {
		close()
		emit(isUpdate ? 'updated' : 'stored')
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
