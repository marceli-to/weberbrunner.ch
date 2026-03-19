<script setup>
import { useRoute, useRouter } from 'vue-router'
import PageTitle from '@/components/ui/PageTitle.vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import BackButton from '@/components/ui/BackButton.vue'
import Tabs from '@/components/ui/navbar/Tabs.vue'

defineProps({
	project: { type: Object, default: null },
})

const route = useRoute()
const router = useRouter()

const tabs = [
	{ label: 'Layout', name: 'projects.layout' },
	{ label: 'Meta / SEO', name: 'projects.metadata' },
	{ label: 'Teaserbild', name: 'projects.teaser_image' },
	{ label: 'Einstellungen', name: 'projects.settings' },
]

function goBack() {
	router.push({ name: 'projects.show', params: { id: route.params.id } })
}
</script>

<template>
	<template v-if="project">

		<!-- NavBar -->
		<Grid class="mb-40">
			<Span class="col-span-8 col-start-2">
				<Tabs :items="tabs" />
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
