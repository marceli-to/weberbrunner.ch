<script setup>
import { ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import projectsApi from '@/api/projects'
import { usePageLoader } from '@/composables/usePageLoader'
import PageTitle from '@/components/ui/PageTitle.vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import Arrow from '@/components/icons/Arrow.vue'
import NavBar from '@/components/ui/nav-bar/NavBar.vue'
import NavBarButton from '@/components/ui/nav-bar/NavBarButton.vue'
import Window from '@/components/icons/Window.vue'
import Download from '@/components/icons/Download.vue'
import List from '@/components/icons/List.vue'
import Eye from '@/components/icons/Eye.vue'
import ProjectImages from './components/Images.vue'
import ProjectMasterData from './components/MasterData.vue'

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

	<!-- NavBar -->
	<Grid v-if="project" class="mb-40">
		<Span class="col-span-8 col-start-2">
			<NavBar>
				<NavBarButton>
					<template #icon>
            <Window class="w-14 h-auto" />
          </template>
					Web
				</NavBarButton>
				<NavBarButton>
					<template #icon>
            <Download class="w-14 h-auto" />
          </template>
					Rohdaten (ZIP)
				</NavBarButton>
				<NavBarButton>
					<template #icon>
            <List class="w-14 h-auto" />
          </template>
					Referenzblatt (PDF)
				</NavBarButton>
				<NavBarButton>
					<template #icon>
            <Eye class="w-14 h-auto" />
          </template>
					Publiziert (Website)
				</NavBarButton>
			</NavBar>
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
