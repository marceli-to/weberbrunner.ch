<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import teamApi from '@/api/team'
import { useFormErrors } from '@/composables/useFormErrors'
import PageTitle from '@/components/ui/PageTitle.vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import Arrow from '@/components/icons/Arrow.vue'
import PencilCircle from '@/components/icons/PencilCircle.vue'
import Input from '@/components/ui/form/Input.vue'
import Button from '@/components/ui/form/Button.vue'

const route = useRoute()
const router = useRouter()
const { get, clear, submit } = useFormErrors()
const member = ref(null)
const editing = ref(false)
const form = ref({})

onMounted(async () => {
	const { data } = await teamApi.show(route.params.id)
	member.value = data.data
})

function goBack() {
	router.push({ name: 'office.team' })
}

function startEditing() {
	form.value = {
		name: member.value.name || '',
		firstname: member.value.firstname || '',
		title: member.value.title || '',
		since: member.value.since || '',
		email: member.value.email || '',
		location_id: member.value.location?.id || null,
	}
	clear()
	editing.value = true
}

function cancelEditing() {
	editing.value = false
	clear()
}

async function save() {
	const ok = await submit(() => teamApi.update(route.params.id, form.value))
	if (ok) {
		const { data } = await teamApi.show(route.params.id)
		member.value = data.data
		editing.value = false
	}
}
</script>

<template>
	<template v-if="member">

		<!-- Header -->
		<Grid class="mb-20">
			<Span class="col-span-1 flex items-center justify-center">
				<button type="button" @click="goBack">
					<Arrow variant="left" class="w-25 cursor-pointer" />
				</button>
			</Span>
			<Span class="col-span-8">
				<PageTitle>
          {{ member.fullname }}
        </PageTitle>
			</Span>
		</Grid>

		<!-- Content -->
		<Grid class="mb-20">

			<Span class="col-span-2 col-start-2">
				[Image]
			</Span>

			<Span class="col-span-6 flex flex-col gap-20">

				<!-- Steckbrief Website: Display mode -->
				<div v-if="!editing" class="bg-white pb-20">
					<Grid :cols="6" class="px-20">
						<Span class="col-span-2 font-semibold text-md min-h-50 flex items-center border-b border-b-thin">
							Steckbrief Website
						</Span>
						<Span class="col-span-4 min-h-50 flex items-center justify-end border-b border-b-thin">
							<button type="button" @click="startEditing">
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
						<Grid :cols="6" class="px-20 pt-6 text-md">
							<Span class="col-span-2 font-semibold pb-6 border-b border-b-gray flex items-center">{{ row.label }}</Span>
							<Span class="col-span-4 pb-6 border-b border-b-gray flex items-center">{{ row.value }}</Span>
						</Grid>
					</div>
				</div>

				<!-- Steckbrief Website: Edit mode -->
				<div v-else class="bg-white pb-20">
					<Grid :cols="6" class="px-20">
						<Span class="col-span-2 font-semibold text-md text-gray min-h-50 flex items-center border-b border-b-thin border-b-white">
							Steckbrief Website
						</Span>
						<Span class="col-span-4 min-h-50 flex items-center justify-end border-b border-b-thin border-b-white">
							<button type="button" @click="cancelEditing">
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
					]" :key="i">
						<Grid :cols="6" class="px-20 pt-3 text-md">
							<Span class="col-span-2 font-semibold pb-3 flex items-center">{{ row.label }}</Span>
							<Span class="col-span-4 pb-3">
								<Input v-model="form[row.field]" :error="get(row.field)" @focus="clear(row.field)" />
							</Span>
						</Grid>
					</div>
					<Grid :cols="6" class="px-20 pt-20">
						<Span class="col-span-2" />
						<Span class="col-span-4 flex flex-col gap-0">
							<Button @click="save">Speichern</Button>
							<Button @click="cancelEditing">Abbrechen</Button>
						</Span>
					</Grid>
				</div>

			</Span>
		</Grid>

	</template>
</template>
