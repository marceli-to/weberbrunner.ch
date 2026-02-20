<script setup>
import { ref, watch } from 'vue'
import Editor from '@/components/ui/form/editor/Editor.vue'
import Button from '@/components/ui/form/Button.vue'
import { useFormErrors } from '@/composables/useFormErrors'

const props = defineProps({
	block: { type: Object, required: true },
})

const emit = defineEmits(['save'])

const { get, clear, submit } = useFormErrors()

const form = ref({
	content: props.block.content || '',
})

watch(() => props.block, (val) => {
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
		<Editor
			v-model="form.content"
			:error="get('content')"
			@focus="clear('content')" />
		<div class="flex justify-end pt-5">
			<Button @click="save" class="flex justify-center">Speichern</Button>
		</div>
	</div>
</template>
