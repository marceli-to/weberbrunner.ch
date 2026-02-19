<script setup>
import { useRouter } from 'vue-router'
import { useProject } from '@/composables/useProject'
import PageTitle from '@/components/ui/PageTitle.vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import Arrow from '@/components/icons/Arrow.vue'
import ProjectNavBar from '@/views/projects/components/navbar/Project.vue'
import ProjectImages from '@/views/projects/components/Images.vue'
import ProjectMasterData from '@/views/projects/components/MasterData.vue'

const router = useRouter()
const { project, fetch } = useProject()

function goBack() {
	router.push({ name: 'projects.index' })
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
			<ProjectMasterData :project="project" />
		</Span>

		<Span class="col-span-8 col-start-2">
			<ProjectImages :project="project" @updated="fetch" />
		</Span>

	</Grid>
  
</template>
