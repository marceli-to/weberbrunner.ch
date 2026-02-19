<script setup>
import { useRoute, useRouter } from 'vue-router'
import projectsApi from '@/api/projects'
import { useProject } from '@/composables/useProject'
import { useToast } from '@/composables/useToast'
import PageTitle from '@/components/ui/PageTitle.vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import Arrow from '@/components/icons/Arrow.vue'
import WebNavBar from '@/views/projects/components/navbar/Web.vue'
import PublishToggle from '@/components/ui/form/PublishToggle.vue'

const route = useRoute()
const router = useRouter()
const { project } = useProject()
const toast = useToast()

function goBack() {
	router.push({ name: 'projects.show', params: { id: route.params.id } })
}

async function togglePublish(value) {
	const previous = project.value.publish
	project.value.publish = value
	try {
		await projectsApi.toggle(route.params.id)
	} catch {
		project.value.publish = previous
		toast.error('Fehler beim Speichern')
	}
}
</script>

<template>

	<!-- NavBar -->
	<Grid v-if="project" class="mb-40">
		<Span class="col-span-8 col-start-2">
			<WebNavBar />
		</Span>
	</Grid>

	<!-- Header -->
	<Grid class="mb-20">

		<Span class="col-span-1 flex items-center justify-center">
			<button type="button" @click="goBack">
				<Arrow variant="left" class="w-25 cursor-pointer" />
			</button>
		</Span>

		<Span class="col-span-8">
			<PageTitle>
				{{ project?.full_title }}
			</PageTitle>
		</Span>

	</Grid>

	<!-- Content -->
	<Grid v-if="project" class="mb-20">
		<Span class="col-span-8 col-start-2">
			<PublishToggle :model-value="project.publish" @update:model-value="togglePublish" />
		</Span>
	</Grid>

</template>
