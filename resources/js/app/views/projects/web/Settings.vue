<script setup>
import { useRoute, useRouter } from 'vue-router'
import { useProjectSettings } from '@/composables/useProjectSettings'
import PageTitle from '@/components/ui/PageTitle.vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import Arrow from '@/components/icons/Arrow.vue'
import Checkbox from '@/components/ui/form/Checkbox.vue'
import Card from '@/components/ui/Card.vue'
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

	<!-- Content -->
	<Grid class="mb-20">
		<Span class="col-span-8 col-start-2">
			<PublishToggle :model-value="project.publish" @update:model-value="togglePublish" />
		</Span>
	</Grid>

	<Grid>

		<!-- Statuses -->
		<Span class="col-span-4 col-start-2">
			<Card has-header>
				<div class="font-semibold text-md min-h-50 flex items-center border-b-thin">
					Status (Website)
				</div>
				<div
					v-for="status in statuses"
					:key="status.id"
					class="min-h-30 text-md flex items-center border-b-thin border-b-gray">
					<Checkbox
						:model-value="isStatusSelected(status.id)"
						:label="status.title"
						@update:model-value="toggleStatus(status.id)" />
				</div>
			</Card>
		</Span>

		<!-- Categories -->
		<Span class="col-span-4">
			<Card has-header>
				<div class="font-semibold text-md min-h-50 flex items-center border-b-thin">
					Kategorie
				</div>
				<div
					v-for="category in categories"
					:key="category.id"
					class="min-h-30 text-md flex items-center border-b-thin border-b-gray">
					<Checkbox
						:model-value="isCategorySelected(category.id)"
						:label="category.title"
						@update:model-value="toggleCategory(category.id)" />
				</div>
			</Card>
		</Span>

	</Grid>

	</template>
  
</template>
