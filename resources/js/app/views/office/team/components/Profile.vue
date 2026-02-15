<script setup>
import { ref } from 'vue'
import teamApi from '@/api/team'
import { useFormErrors } from '@/composables/useFormErrors'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import PencilCircle from '@/components/icons/PencilCircle.vue'
import Input from '@/components/ui/form/Input.vue'
import Button from '@/components/ui/form/Button.vue'

const props = defineProps({
	member: { type: Object, required: true },
})

const emit = defineEmits(['updated'])

const { get, clear, submit } = useFormErrors()
const editing = ref(false)
const form = ref({})

function startEditing() {
	form.value = {
		name: props.member.name || '',
		firstname: props.member.firstname || '',
		title: props.member.title || '',
		since: props.member.since || '',
		email: props.member.email || '',
		location_id: props.member.location?.id || null,
	}
	clear()
	editing.value = true
}

function cancelEditing() {
	editing.value = false
	clear()
}

async function save() {
	const ok = await submit(() => teamApi.update(props.member.uuid, form.value))
	if (ok) {
		editing.value = false
		emit('updated')
	}
}
</script>

<template>
	<!-- Display mode -->
	<div v-if="!editing" class="bg-white pb-20">
		<Grid :cols="6" class="px-20">
			<Span class="col-span-2 font-semibold text-md min-h-50 flex items-center border-b border-b-thin">
				Steckbrief Website
			</Span>
			<Span class="col-span-4 min-h-50 flex items-center justify-end border-b border-b-thin">
				<button type="button" @click="startEditing" class="cursor-pointer">
					<PencilCircle class="w-25" />
				</button>
			</Span>
		</Grid>
		<div v-for="(row, i) in [
			{ label: 'Nachname', value: member.name },
			{ label: 'Vorname', value: member.firstname },
			{ label: 'Ausbildung / Funktion', value: member.title },
			{ label: 'Standort', value: member.location?.title },
			{ label: 'Mitarbeit seit', value: member.since },
			{ label: 'E-Mail-Adresse', value: member.email },
		]" :key="i">
			<Grid :cols="6" class="px-20 min-h-30 text-md">
				<Span class="col-span-2 font-semibold border-b border-b-gray flex items-center">{{ row.label }}</Span>
				<Span class="col-span-4 border-b border-b-gray flex items-center">{{ row.value }}</Span>
			</Grid>
		</div>
	</div>

	<!-- Edit mode -->
	<form v-else @submit.prevent="save" class="bg-white pb-20">
		<Grid :cols="6" class="px-20">
			<Span class="col-span-2 font-semibold text-md text-gray min-h-50 flex items-center border-b border-b-thin border-b-white">
				Steckbrief Website
			</Span>
			<Span class="col-span-4 min-h-50 flex items-center justify-end border-b border-b-thin border-b-white">
				<button type="button" @click="cancelEditing" class="cursor-pointer">
					<PencilCircle class="w-25 text-gray" />
				</button>
			</Span>
		</Grid>
		<div v-for="(row, i) in [
			{ label: 'Nachname', field: 'name' },
			{ label: 'Vorname', field: 'firstname' },
			{ label: 'Ausbildung / Funktion', field: 'title' },
			{ label: 'Standort', field: 'location_id' },
			{ label: 'Mitarbeit seit', field: 'since' },
			{ label: 'E-Mail-Adresse', field: 'email' },
		]" :key="i" class="mb-10">
			<Grid :cols="6" class="px-20 text-md">
				<Span class="col-span-2 font-semibold flex items-center">{{ row.label }}</Span>
				<Span class="col-span-4">
					<Input v-model="form[row.field]" :error="get(row.field)" @focus="clear(row.field)" />
				</Span>
			</Grid>
		</div>
		<Grid :cols="6" class="px-20 pt-20">
			<Span class="col-span-2" />
			<Span class="col-span-4 flex flex-col gap-10">
				<Button type="submit" class="px-10">Speichern</Button>
				<Button type="button" @click="cancelEditing" class="px-10">Abbrechen</Button>
			</Span>
		</Grid>
	</form>
</template>
