<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useProject } from '@/composables/useProject'
import { useCan } from '@/composables/useCan'
import PageTitle from '@/components/ui/PageTitle.vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import BackButton from '@/components/ui/BackButton.vue'
import ProjectNavBar from '@/views/projects/components/ProjectTabs.vue'
import ProjectImages from '@/views/projects/components/ImageGrid.vue'
import ProjectMasterData from '@/views/projects/components/MasterData.vue'
import ProjectText from '@/views/projects/components/TextCards.vue'
import TitleLightbox from '@/views/projects/components/TitleLightbox.vue'

const router = useRouter()
const { project, fetch } = useProject()
const { canUpdate } = useCan()
const titleLightbox = ref(null)

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
			<BackButton @click="goBack" />
		</Span>

		<Span class="col-span-8">
			<PageTitle :editable="canUpdate" @edit="titleLightbox.open(project)">
				{{ project?.full_title }}
			</PageTitle>
		</Span>

	</Grid>


	<!-- Content -->
	<Grid v-if="project" class="mb-20">

		<Span class="col-span-8 col-start-2">
			<ProjectImages :project="project" @updated="fetch" />
		</Span>

    <Span class="col-span-8 col-start-2">
      <ProjectText :project="project" />
    </Span>

		<Span class="col-span-8 col-start-2">
			<ProjectMasterData :project="project" />
		</Span>

	</Grid>

	<TitleLightbox ref="titleLightbox" @saved="fetch" />

</template>
