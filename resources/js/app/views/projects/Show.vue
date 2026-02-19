<script setup>
import { ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import projectsApi from '@/api/projects'
import { usePageLoader } from '@/composables/usePageLoader'
import PageTitle from '@/components/ui/PageTitle.vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import Arrow from '@/components/icons/Arrow.vue'
import ProjectImages from './components/ProjectImages.vue'

const route = useRoute()
const router = useRouter()
const { load } = usePageLoader()
const project = ref(null)

async function fetch() {
	const { data } = await projectsApi.show(route.params.id)
	project.value = data.data
}

function goBack() {
	router.push({ name: 'projects.index' })
}

load(fetch)
</script>

<template>
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
			<ProjectImages :project="project" @updated="fetch" />
		</Span>
	</Grid>
</template>
