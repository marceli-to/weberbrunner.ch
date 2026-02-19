<script setup>
import { useRoute, useRouter } from 'vue-router'
import { useProjectSettings } from '@/composables/useProjectSettings'
import PageTitle from '@/components/ui/PageTitle.vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import BackButton from '@/components/ui/BackButton.vue'
import Checkbox from '@/components/ui/form/Checkbox.vue'
import Card from '@/components/ui/Card.vue'
import CardRow from '@/components/ui/CardRow.vue'
import WebNavBar from '@/views/projects/components/navbar/Web.vue'
import PublishToggle from '@/components/ui/form/PublishToggle.vue'

const route = useRoute()
const router = useRouter()

const {
	project,
	statuses,
	categories,
	isStatusSelected,
	isCategorySelected,
	togglePublish,
	toggleStatus,
	toggleCategory,
} = useProjectSettings()

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
			<PageTitle>
				{{ project.full_title }}
			</PageTitle>
		</Span>

	</Grid>

	<!-- Content -->
	<Grid class="mb-20">
		<Span class="col-span-8 col-start-2">
			<PublishToggle :model-value="project.publish" @update:model-value="togglePublish" />
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
					<Checkbox
						:model-value="isStatusSelected(status.id)"
						:label="status.title"
						@update:model-value="toggleStatus(status.id)" />
				</CardRow>
			</Card>
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
					<Checkbox
						:model-value="isCategorySelected(category.id)"
						:label="category.title"
						@update:model-value="toggleCategory(category.id)" />
				</CardRow>
			</Card>
		</Span>

	</Grid>

	</template>
  
</template>
