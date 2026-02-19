<script setup>
import { useRoute, useRouter } from 'vue-router'
import { useProject } from '@/composables/useProject'
import PageTitle from '@/components/ui/PageTitle.vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import Arrow from '@/components/icons/Arrow.vue'
import WebNavBar from '@/views/projects/components/navbar/Web.vue'

const route = useRoute()
const router = useRouter()
const { project } = useProject()

function goBack() {
	router.push({ name: 'projects.show', params: { id: route.params.id } })
}
</script>

<template>

	<template v-if="project">

	<!-- NavBar -->
	<Grid class="mb-40">
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
				{{ project.full_title }}
			</PageTitle>
		</Span>

	</Grid>

	</template>

</template>
