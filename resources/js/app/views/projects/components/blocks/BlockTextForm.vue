<script setup>
import { ref, watch } from 'vue'
import Input from '@/components/ui/form/Input.vue'
import Editor from '@/components/ui/form/editor/Editor.vue'
import Button from '@/components/ui/form/Button.vue'
import { useFormErrors } from '@/composables/useFormErrors'

const props = defineProps({
	block: { type: Object, required: true },
})

const emit = defineEmits(['save'])

const { get, clear, submit } = useFormErrors()

const form = ref({
	title: props.block.title || '',
	content: props.block.content || '',
})

watch(() => props.block, (val) => {
	form.value.title = val.title || ''
	form.value.content = val.content || ''
})

async function save() {
	const ok = await submit(() => {
		emit('save', { ...form.value })
		return Promise.resolve()
	})
}
</script>

<template>
	<div class="flex flex-col gap-y-10 pt-10">
		<Input
			v-model="form.title"
			placeholder="Titel"
			:error="get('title')"
			@focus="clear('title')" />
		<Editor
			v-model="form.content"
			:error="get('content')"
			@focus="clear('content')" />
		<div class="flex justify-end pt-5">
			<Button variant="primary" @click="save">Speichern</Button>
		</div>
	</div>
</template>
