<script setup>
import { useProjectSettings } from '@/composables/useProjectSettings'
import { useCan } from '@/composables/useCan'
import WebLayout from '@/views/projects/components/Layout.vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import Checkbox from '@/components/ui/form/Checkbox.vue'
import Radio from '@/components/ui/form/Radio.vue'
import Card from '@/components/ui/Card.vue'
import CardRow from '@/components/ui/CardRow.vue'
import PublishToggle from '@/components/ui/form/PublishToggle.vue'

const {
	project,
	statuses,
	categories,
	selectedStatusId,
	isCategorySelected,
	togglePublish,
	selectStatus,
	toggleCategory,
} = useProjectSettings()

const { canUpdate } = useCan()
</script>

<template>
	<WebLayout :project="project">

		<!-- Publish -->
		<Grid class="mb-20">
			<Span class="col-span-8 col-start-2">
				<template v-if="canUpdate">
					<PublishToggle :model-value="project.publish" @update:model-value="togglePublish" />
				</template>
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
						<Radio
							:model-value="selectedStatusId()"
							:value="status.id"
							:label="status.title"
							name="status"
							:disabled="!canUpdate"
							@update:model-value="selectStatus(status.id)" />
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
							:disabled="!canUpdate"
							@update:model-value="toggleCategory(category.id)" />
					</CardRow>
				</Card>
			</Span>
		</Grid>

	</WebLayout>
</template>
