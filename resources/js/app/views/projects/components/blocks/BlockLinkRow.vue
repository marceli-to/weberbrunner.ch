<script setup>
import { ref, watch } from 'vue'
import Input from '@/components/ui/form/Input.vue'
import Select from '@/components/ui/form/Select.vue'
import Button from '@/components/ui/form/Button.vue'
import Cross from '@/components/icons/Cross.vue'
import Burger from '@/components/icons/Burger.vue'

const props = defineProps({
	link: { type: Object, required: true },
	projects: { type: Array, default: () => [] },
})

const emit = defineEmits(['save', 'delete'])

const form = ref({
	title: props.link.title || '',
	url: props.link.url || '',
	link_type: props.link.link_type || 'external',
	linked_project_id: props.link.linked_project_id || '',
})

watch(() => props.link, (val) => {
	form.value.title = val.title || ''
	form.value.url = val.url || ''
	form.value.link_type = val.link_type || 'external'
	form.value.linked_project_id = val.linked_project_id || ''
})

const linkTypeOptions = [
	{ value: 'external', label: 'Extern' },
	{ value: 'internal', label: 'Intern' },
]

function save() {
	emit('save', { ...form.value })
}
</script>

<template>
	<div class="flex items-start gap-x-10 border-b-thin pb-10">
		<Burger variant="sm" class="w-18 h-10 cursor-grab drag-handle shrink-0 mt-8" />
		<div class="flex-1 flex flex-col gap-y-5">
			<div class="flex gap-x-10">
				<div class="flex-1">
					<Input v-model="form.title" placeholder="Titel" />
				</div>
				<div class="w-120">
					<Select v-model="form.link_type" :options="linkTypeOptions" />
				</div>
			</div>
			<div v-if="form.link_type === 'external'">
				<Input v-model="form.url" placeholder="https://..." />
			</div>
			<div v-else>
				<Select
					v-model="form.linked_project_id"
					:options="projects.map(p => ({ value: p.id, label: p.full_title || p.title }))">
					<option value="">Projekt wählen...</option>
					<option
						v-for="p in projects"
						:key="p.id"
						:value="p.id">
						{{ p.full_title || p.title }}
					</option>
				</Select>
			</div>
			<div class="flex justify-end">
				<Button variant="ghost" @click="save">Speichern</Button>
			</div>
		</div>
		<button type="button" class="cursor-pointer shrink-0 mt-8" @click="$emit('delete')">
			<Cross class="w-10" />
		</button>
	</div>
</template>
