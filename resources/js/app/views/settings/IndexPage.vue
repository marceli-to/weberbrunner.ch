<script setup>
import { ref } from 'vue'
import categoriesApi from '@/api/categories'
import statusesApi from '@/api/statuses'
import { usePageLoader } from '@/composables/usePageLoader'
import { useCollapsed } from '@/composables/useCollapsed'
import { useConfirm } from '@/composables/useConfirm'
import draggable from 'vuedraggable'
import PageTitle from '@/components/ui/PageTitle.vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import CollapsibleHeader from '@/components/ui/CollapsibleHeader.vue'
import DraggableEntryRow from '@/components/ui/DraggableEntryRow.vue'
import NewEntryButton from '@/components/ui/NewEntryButton.vue'
import SectionTitleForm from '@/components/ui/SectionTitleForm.vue'

const { load } = usePageLoader()
const statuses = ref([])
const categories = ref([])
const statusLightbox = ref(null)
const categoryLightbox = ref(null)
const { collapsed, toggle } = useCollapsed('settings')
const { confirm } = useConfirm()

async function fetch() {
	const [statusesRes, categoriesRes] = await Promise.all([
		statusesApi.index(),
		categoriesApi.index(),
	])
	statuses.value = statusesRes.data.data
	categories.value = categoriesRes.data.data
}

async function destroyStatus(status) {
	const ok = await confirm({
		message: 'Möchtest Du diesen Eintrag wirklich löschen?',
		confirmLabel: 'Löschen',
		variant: 'danger',
	})
	if (ok) {
		await statusesApi.destroy(status.uuid)
		await fetch()
	}
}

async function destroyCategory(category) {
	const ok = await confirm({
		message: 'Möchtest Du diesen Eintrag wirklich löschen?',
		confirmLabel: 'Löschen',
		variant: 'danger',
	})
	if (ok) {
		await categoriesApi.destroy(category.uuid)
		await fetch()
	}
}

async function reorderStatuses() {
	const items = statuses.value.map((s, i) => ({
		uuid: s.uuid,
		sort_order: i,
	}))
	await statusesApi.reorder(items)
}

async function reorderCategories() {
	const items = categories.value.map((c, i) => ({
		uuid: c.uuid,
		sort_order: i,
	}))
	await categoriesApi.reorder(items)
}

load(fetch)
</script>

<template>

	<!-- Header -->
	<Grid class="mb-40">
		<Span class="col-span-8 col-start-2">
			<PageTitle>Voreinstellungen</PageTitle>
		</Span>
	</Grid>

	<!-- Settings -->
	<Grid>

		<div class="col-span-10 flex flex-col gap-20">

			<!-- Statuses -->
			<Span class="col-span-10">

				<Grid :cols="10">

					<Span class="col-span-8 col-start-2">
						<CollapsibleHeader
							title="Status"
							:collapsed="collapsed.has('statuses')"
							@toggle="toggle('statuses')" />
					</Span>

					<Span v-show="!collapsed.has('statuses')" class="col-span-10 col-start-1">
						<draggable
							v-model="statuses"
							item-key="uuid"
							handle=".status-drag-handle"
							ghost-class="opacity-50"
							animation="150"
							class="flex flex-col gap-10 min-h-1"
							:class="{ 'mb-10': statuses.length }"
							@change="reorderStatuses">
							<template #item="{ element: status }">
								<DraggableEntryRow
									:label="status.title"
									:show-publish="false"
									drag-handle-class="status-drag-handle"
									@edit="statusLightbox.edit(status)"
									@delete="destroyStatus(status)" />
							</template>
						</draggable>

						<Grid :cols="10" class="mb-10">
							<Span class="col-span-8 col-start-2">
								<NewEntryButton @click="statusLightbox.open()" />
							</Span>
						</Grid>
					</Span>

				</Grid>

			</Span>

			<!-- Categories -->
			<Span class="col-span-10">

				<Grid :cols="10">

					<Span class="col-span-8 col-start-2">
						<CollapsibleHeader
							title="Kategorie"
							:collapsed="collapsed.has('categories')"
							@toggle="toggle('categories')" />
					</Span>

					<Span v-show="!collapsed.has('categories')" class="col-span-10 col-start-1">
						<draggable
							v-model="categories"
							item-key="uuid"
							handle=".category-drag-handle"
							ghost-class="opacity-50"
							animation="150"
							class="flex flex-col gap-10 min-h-1"
							:class="{ 'mb-10': categories.length }"
							@change="reorderCategories">
							<template #item="{ element: category }">
								<DraggableEntryRow
									:label="category.title"
									:show-publish="false"
									drag-handle-class="category-drag-handle"
									@edit="categoryLightbox.edit(category)"
									@delete="destroyCategory(category)" />
							</template>
						</draggable>

						<Grid :cols="10" class="mb-10">
							<Span class="col-span-8 col-start-2">
								<NewEntryButton @click="categoryLightbox.open()" />
							</Span>
						</Grid>
					</Span>

				</Grid>

			</Span>

		</div>

	</Grid>

	<SectionTitleForm
		ref="statusLightbox"
		label="Status"
		create-label="Neuer Status"
		:store-fn="statusesApi.store"
		:update-fn="statusesApi.update"
		@stored="fetch" />

	<SectionTitleForm
		ref="categoryLightbox"
		label="Kategorie"
		create-label="Neue Kategorie"
		:store-fn="categoriesApi.store"
		:update-fn="categoriesApi.update"
		@stored="fetch" />

</template>
