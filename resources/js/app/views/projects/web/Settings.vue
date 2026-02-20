<script setup>
import { useProjectSettings } from '@/composables/useProjectSettings'
import WebPageLayout from '@/views/projects/components/WebPageLayout.vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import Checkbox from '@/components/ui/form/Checkbox.vue'
import Card from '@/components/ui/Card.vue'
import CardRow from '@/components/ui/CardRow.vue'
import PublishToggle from '@/components/ui/form/PublishToggle.vue'

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
</script>

<template>
	<WebPageLayout :project="project">

		<!-- Publish -->
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

	</WebPageLayout>
</template>
