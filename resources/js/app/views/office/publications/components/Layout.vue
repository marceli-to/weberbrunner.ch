<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { usePublication } from '@/composables/usePublication'
import PageTitle from '@/components/ui/PageTitle.vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import BackButton from '@/components/ui/BackButton.vue'
import Tabs from '@/components/ui/navbar/Tabs.vue'
import TitleLightbox from '@/views/office/publications/components/TitleLightbox.vue'

defineProps({
	publication: { type: Object, default: null },
})

const router = useRouter()
const { fetch } = usePublication(null, { skipFetch: true })
const titleLightbox = ref(null)

const tabs = [
	{ label: 'Layout', name: 'publications.show' },
	{ label: 'Meta / SEO', name: 'publications.metadata' },
	{ label: 'Teaserbild', name: 'publications.teaser_image' },
	{ label: 'Einstellungen', name: 'publications.settings' },
]

function goBack() {
	router.push({ name: 'office.publications' })
}
</script>

<template>
	<template v-if="publication">

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
				<PageTitle editable :slug="publication.slug" preview-prefix="/vorschau/publikationen" @edit="titleLightbox.open(publication)">
					{{ publication.title }}
				</PageTitle>
			</Span>
		</Grid>

		<!-- Page content -->
		<slot />

	</template>

	<TitleLightbox ref="titleLightbox" @saved="fetch" />
</template>
