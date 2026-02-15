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
const bioForms = ref([])

function startEditing() {
	bioForms.value = props.member.bios.map(bio => ({
		uuid: bio.uuid,
		period: bio.period || '',
		description: bio.description || '',
	}))
	clear()
	editing.value = true
}

function cancelEditing() {
	editing.value = false
	clear()
}

async function save() {
	let allOk = true
	for (const bio of bioForms.value) {
		const ok = await submit(() => teamApi.bios.update(props.member.uuid, bio.uuid, {
			period: bio.period,
			description: bio.description,
		}))
		if (!ok) {
			allOk = false
			break
		}
	}
	if (allOk) {
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
				Lebenslauf Website
			</Span>
			<Span class="col-span-4 min-h-50 flex items-center justify-end border-b border-b-thin">
				<button type="button" @click="startEditing" class="cursor-pointer">
					<PencilCircle class="w-25" />
				</button>
			</Span>
		</Grid>
		<div v-for="bio in member.bios" :key="bio.uuid">
			<Grid :cols="6" class="px-20 min-h-30 text-md">
				<Span class="col-span-2 font-semibold border-b border-b-gray flex items-center">{{ bio.period }}</Span>
				<Span class="col-span-4 border-b border-b-gray flex items-center">{{ bio.description }}</Span>
			</Grid>
		</div>
	</div>

	<!-- Edit mode -->
	<form v-else @submit.prevent="save" class="bg-white pb-20">
		<Grid :cols="6" class="px-20">
			<Span class="col-span-2 font-semibold text-md text-gray min-h-50 flex items-center border-b border-b-thin border-b-white">
				Lebenslauf Website
			</Span>
			<Span class="col-span-4 min-h-50 flex items-center justify-end border-b border-b-thin border-b-white">
				<button type="button" @click="cancelEditing" class="cursor-pointer">
					<PencilCircle class="w-25 text-gray" />
				</button>
			</Span>
		</Grid>
		<div v-for="(bio, i) in bioForms" :key="bio.uuid" class="mb-10">
			<Grid :cols="6" class="px-20 text-md">
				<Span class="col-span-2">
					<Input v-model="bio.period" />
				</Span>
				<Span class="col-span-4 pb-3">
					<Input v-model="bio.description" />
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
