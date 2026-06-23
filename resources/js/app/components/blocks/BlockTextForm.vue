<script setup>
import { ref, watch } from 'vue'
import Editor from '@/components/ui/form/editor/Editor.vue'
import Button from '@/components/ui/form/Button.vue'
import { useCan } from '@/composables/useCan'

const { canUpdate } = useCan()

const props = defineProps({
	block: { type: Object, required: true },
})

const emit = defineEmits(['save'])

const form = ref({
	content: props.block.content || '',
})

watch(() => props.block, (val) => {
	form.value.content = val.content || ''
})

function save() {
	emit('save', { ...form.value })
}
</script>

<template>
	<div class="flex flex-col gap-y-10 pt-10">
		<Editor v-model="form.content" />
		<div v-if="canUpdate" class="flex justify-end pt-5">
			<Button @click="save" class="flex justify-center">Speichern</Button>
		</div>
	</div>
</template>
