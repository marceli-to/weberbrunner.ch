<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useProject } from '@/composables/useProject'
import projectMasterdataApi from '@/api/project-masterdata'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import PageTitle from '@/components/ui/PageTitle.vue'
import SectionTitle from '@/components/ui/SectionTitle.vue'
import BackButton from '@/components/ui/BackButton.vue'
import ProjectNavBar from '@/views/projects/components/navbar/Project.vue'
import FormContainer from '@/components/ui/form/FormContainer.vue'
import ActionBar from '@/components/ui/form/ActionBar.vue'
import MasterdataInputRow from '@/components/ui/MasterdataInputRow.vue'

const route = useRoute()
const router = useRouter()

const { project } = useProject()

const entries = ref([])
const form = ref({})

onMounted(async () => {
	const { data } = await projectMasterdataApi.all(route.params.id)
	entries.value = data.data
	form.value = Object.fromEntries(entries.value.map(e => [e.uuid, e.value ?? '']))
})

function goBack() {
	router.push({ name: 'projects.show', params: { id: route.params.id } })
}

async function handleSubmit() {
	const payload = entries.value
		.filter(e => form.value[e.uuid] !== '' && form.value[e.uuid] != null)
		.map(e => ({ uuid: e.uuid, value: form.value[e.uuid] }))
	await projectMasterdataApi.updateValues(route.params.id, payload)
	goBack()
}
</script>

<template>

	<!-- NavBar -->
	<Grid v-if="project" class="mb-40">
		<Span class="col-span-8 col-start-2">
			<ProjectNavBar />
		</Span>
	</Grid>

	<!-- Header -->
	<Grid class="mb-20">
		<Span class="col-span-1 flex items-center justify-center">
			<BackButton @click="goBack" />
		</Span>
		<Span class="col-span-8">
			<PageTitle :slug="project?.slug">
				{{ project?.full_title }}
			</PageTitle>
		</Span>
	</Grid>

	<!-- Content -->
	<FormContainer v-if="project" @submit="handleSubmit">
		<Grid class="mb-20">
			<Span class="col-span-8 col-start-2">
				<SectionTitle>Stammdaten</SectionTitle>
				<div class="flex flex-col gap-10 mt-10">
					<MasterdataInputRow
						v-for="entry in entries"
						:key="entry.uuid"
						:label="entry.title"
						v-model="form[entry.uuid]" />
				</div>
			</Span>
		</Grid>

		<ActionBar @cancel="goBack" />
	</FormContainer>

</template>
