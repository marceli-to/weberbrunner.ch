<script setup>
import { ref, onMounted } from 'vue'
import categoriesApi from '@/api/categories'
import statusesApi from '@/api/statuses'
import { usePageLoader } from '@/composables/usePageLoader'
import PageTitle from '@/components/ui/PageTitle.vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import Card from '@/components/ui/Card.vue'
import CardRow from '@/components/ui/CardRow.vue'
import NewEntryButton from '@/components/ui/NewEntryButton.vue'
import CreateSectionLightbox from '@/components/ui/CreateSectionLightbox.vue'

const statuses = ref([])
const categories = ref([])
const statusLightbox = ref(null)
const categoryLightbox = ref(null)

async function load() {
	const [statusesRes, categoriesRes] = await Promise.all([
		statusesApi.index(),
		categoriesApi.index(),
	])
	statuses.value = statusesRes.data.data
	categories.value = categoriesRes.data.data
}

onMounted(async () => {
	await load()
	usePageLoader().hide()
})
</script>

<template>
	<!-- Header -->
	<Grid class="mb-40">
		<Span class="col-span-8 col-start-2">
			<PageTitle>Voreinstellungen</PageTitle>
		</Span>
	</Grid>

	<Grid>
		<!-- Statuses -->
		<Span class="col-span-4 col-start-2">
			<Card header>
				<CardRow header>
					Status
				</CardRow>
				<CardRow
					v-for="status in statuses"
					:key="status.id">
					{{ status.title }}
				</CardRow>
			</Card>
			<NewEntryButton @click="statusLightbox.open()" class="mt-20" />
			<CreateSectionLightbox
				ref="statusLightbox"
				lightbox-title="Neuer Status"
				:store-fn="statusesApi.store"
				@stored="load" />
		</Span>

		<!-- Categories -->
		<Span class="col-span-4">
			<Card header>
				<CardRow header>
					Kategorie
				</CardRow>
				<CardRow
					v-for="category in categories"
					:key="category.id">
					{{ category.title }}
				</CardRow>
			</Card>
			<NewEntryButton @click="categoryLightbox.open()" class="mt-20" />
			<CreateSectionLightbox
				ref="categoryLightbox"
				lightbox-title="Neue Kategorie"
				:store-fn="categoriesApi.store"
				@stored="load" />
		</Span>
	</Grid>
</template>
