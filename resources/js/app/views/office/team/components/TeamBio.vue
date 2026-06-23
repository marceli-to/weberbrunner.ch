<script setup>
import { ref } from 'vue'
import teamApi from '@/api/team'
import { useFormErrors } from '@/composables/useFormErrors'
import { useCan } from '@/composables/useCan'
import Card from '@/components/ui/Card.vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import PencilCircle from '@/components/icons/PencilCircle.vue'
import Cross from '@/components/icons/Cross.vue'
import Input from '@/components/ui/form/Input.vue'
import Button from '@/components/ui/form/Button.vue'

const props = defineProps({
	member: { type: Object, required: true },
})

const emit = defineEmits(['updated'])

const { get, clear, submit } = useFormErrors()
const { canCreate, canUpdate, canDelete } = useCan()
const editing = ref(false)
const bioForms = ref([])
const removedUuids = ref([])
const failedIndex = ref(null)

function startEditing() {
	bioForms.value = props.member.bios.map(bio => ({
		uuid: bio.uuid,
		isNew: false,
		period: bio.period || '',
		description: bio.description || '',
	}))
	removedUuids.value = []
	clear()
	editing.value = true
}

function addRow() {
	bioForms.value.push({
		uuid: null,
		isNew: true,
		period: '',
		description: '',
	})
}

function removeRow(index) {
	const bio = bioForms.value[index]
	if (!bio.isNew && bio.uuid) {
		removedUuids.value.push(bio.uuid)
	}
	bioForms.value.splice(index, 1)
}

function cancelEditing() {
	editing.value = false
	failedIndex.value = null
	removedUuids.value = []
	clear()
}

async function save() {
	failedIndex.value = null
	let allOk = true

	for (const uuid of removedUuids.value) {
		const ok = await submit(() => teamApi.bios.destroy(props.member.uuid, uuid))
		if (!ok) {
			allOk = false
			break
		}
	}

	if (!allOk) return

	for (let i = 0; i < bioForms.value.length; i++) {
		const bio = bioForms.value[i]
		const payload = { period: bio.period, description: bio.description }
		const ok = bio.isNew
			? await submit(() => teamApi.bios.store(props.member.uuid, payload))
			: await submit(() => teamApi.bios.update(props.member.uuid, bio.uuid, payload))
		if (!ok) {
			failedIndex.value = i
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

	<Card header>

		<!-- Display mode -->
		<template v-if="!editing">
			<Grid :cols="6">
				<Span class="col-span-2 font-semibold text-md min-h-50 flex items-center border-b-thin">
					Lebenslauf Website
				</Span>
				<Span class="col-span-4 min-h-50 flex items-center justify-end border-b-thin">
					<template v-if="canUpdate">
						<button type="button" @click="startEditing" class="cursor-pointer">
							<PencilCircle class="w-25" />
						</button>
					</template>
				</Span>
			</Grid>
			<div v-for="bio in member.bios" :key="bio.uuid">
				<Grid :cols="6" class="min-h-30 text-md">
					<Span class="col-span-2 font-semibold border-b-thin border-b-gray flex items-center">{{ bio.period }}</Span>
					<Span class="col-span-4 border-b-thin border-b-gray flex items-center">{{ bio.description }}</Span>
				</Grid>
			</div>
		</template>

		<!-- Edit mode -->
		<template v-else>
			<form @submit.prevent="save">
				<Grid :cols="6">
					<Span class="col-span-2 font-semibold text-md text-gray min-h-50 flex items-center border-b-thin border-b-white">
						Lebenslauf Website
					</Span>
					<Span class="col-span-4 min-h-50 flex items-center justify-end border-b-thin border-b-white">
						<button type="button" @click="cancelEditing" class="cursor-pointer">
							<PencilCircle class="w-25 text-gray" />
						</button>
					</Span>
				</Grid>
				<div v-for="(bio, i) in bioForms" :key="bio.uuid ?? `new-${i}`" class="mb-10">
					<Grid :cols="6" class="text-md">
						<Span class="col-span-2">
							<Input v-model="bio.period" :error="i === failedIndex ? get('period') : null" @focus="clear('period')" />
						</Span>
						<Span class="col-span-4 pb-3 flex items-center gap-10">
							<Input v-model="bio.description" :error="i === failedIndex ? get('description') : null" @focus="clear('description')" class="flex-1" />
							<template v-if="canDelete">
								<button type="button" @click="removeRow(i)" class="cursor-pointer shrink-0">
									<Cross class="w-10 text-black" />
								</button>
							</template>
						</Span>
					</Grid>
				</div>
				<Grid :cols="6" class="pt-20">
					<Span class="col-span-2" />
					<Span class="col-span-4 flex flex-col gap-10">
						<template v-if="canCreate">
							<Button type="button" @click="addRow" class="px-10">Eintrag hinzufügen</Button>
						</template>
						<template v-if="canUpdate">
							<Button type="submit" class="px-10">Speichern</Button>
						</template>
						<Button type="button" @click="cancelEditing" class="px-10">Abbrechen</Button>
					</Span>
				</Grid>
			</form>
		</template>

	</Card>

</template>
