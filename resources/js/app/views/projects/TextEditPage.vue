<script setup>
import { useRoute, useRouter } from 'vue-router'
import projectsApi from '@/api/projects'
import { useProject } from '@/composables/useProject'
import { useCollapsed } from '@/composables/useCollapsed'
import { useFormErrors } from '@/composables/useFormErrors'
import { useToast } from '@/composables/useToast'
import PageTitle from '@/components/ui/PageTitle.vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import BackButton from '@/components/ui/BackButton.vue'
import Card from '@/components/ui/Card.vue'
import CollapsibleHeader from '@/components/ui/CollapsibleHeader.vue'
import Textarea from '@/components/ui/form/Textarea.vue'
import Button from '@/components/ui/form/Button.vue'
import ProjectNavBar from '@/views/projects/components/navbar/Project.vue'

const route = useRoute()
const router = useRouter()
const toast = useToast()
const { submit } = useFormErrors()
const { project, fetch } = useProject()
const { collapsed, toggle } = useCollapsed('project-text')

function goBack() {
	router.push({ name: 'projects.show', params: { id: route.params.id } })
}

async function saveDescription() {
	const ok = await submit(() => projectsApi.updateText(route.params.id, {
		description: project.value.description,
	}))
	if (!ok) return
	await fetch()
	toast.success('Gespeichert')
}

async function saveShortDescription() {
	const ok = await submit(() => projectsApi.updateText(route.params.id, {
		short_description: project.value.short_description,
	}))
	if (!ok) return
	await fetch()
	toast.success('Gespeichert')
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

	<!-- Projektbeschrieb -->
	<Grid v-if="project" class="mb-20">
		<Span class="col-span-8 col-start-2">
			<CollapsibleHeader
				:title="'Projektbeschrieb'"
				:collapsed="collapsed.has('description')"
				@toggle="toggle('description')" />
		</Span>
		<Span v-show="!collapsed.has('description')" class="col-span-8 col-start-2">
			<Card>
				<form @submit.prevent="saveDescription">
					<Textarea v-model="project.description" />
					<div class="flex gap-20 mt-10">
						<Button type="submit" class="flex justify-center">Speichern</Button>
					</div>
				</form>
			</Card>
		</Span>
	</Grid>

	<!-- Kurztext -->
	<Grid v-if="project" class="mb-20">
		<Span class="col-span-8 col-start-2">
			<CollapsibleHeader
				:title="'Kurztext'"
				:collapsed="collapsed.has('short-description')"
				@toggle="toggle('short-description')" />
		</Span>
		<Span v-show="!collapsed.has('short-description')" class="col-span-8 col-start-2">
			<Card>
				<form @submit.prevent="saveShortDescription">
					<Textarea v-model="project.short_description" />
					<div class="flex gap-20 mt-10">
						<Button type="submit" class="flex justify-center">Speichern</Button>
					</div>
				</form>
			</Card>
		</Span>
	</Grid>

</template>
