<script setup>
import { useRoute, useRouter } from 'vue-router'
import PageTitle from '@/components/ui/PageTitle.vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import BackButton from '@/components/ui/BackButton.vue'
import WebNavBar from '@/views/projects/components/navbar/Web.vue'

defineProps({
	project: { type: Object, default: null },
})

const route = useRoute()
const router = useRouter()

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
				<BackButton @click="goBack" />
			</Span>
			<Span class="col-span-8">
				<PageTitle :slug="project.slug">
					{{ project.full_title }}
				</PageTitle>
			</Span>
		</Grid>

		<!-- Page content -->
		<slot />

	</template>
</template>
