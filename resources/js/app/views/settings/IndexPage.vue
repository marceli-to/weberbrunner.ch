<script setup>
import { ref } from 'vue'
import categoriesApi from '@/api/categories'
import statusesApi from '@/api/statuses'
import masterdataApi from '@/api/masterdata'
import masterdataGroupsApi from '@/api/masterdata-groups'
import { usePageLoader } from '@/composables/usePageLoader'
import { useCollapsed } from '@/composables/useCollapsed'
import { useConfirm } from '@/composables/useConfirm'
import { useCan } from '@/composables/useCan'
import draggable from 'vuedraggable'
import PageTitle from '@/components/ui/PageTitle.vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import Button from '@/components/ui/form/Button.vue'
import Burger from '@/components/icons/Burger.vue'
import CollapsibleHeader from '@/components/ui/CollapsibleHeader.vue'
import Cross from '@/components/icons/Cross.vue'
import Plus from '@/components/icons/Plus.vue'
import DraggableEntryRow from '@/components/ui/DraggableEntryRow.vue'
import NewEntryButton from '@/components/ui/NewEntryButton.vue'
import SectionTitleForm from '@/components/ui/SectionTitleForm.vue'
import MasterdataEntryForm from '@/components/ui/MasterdataEntryForm.vue'

const { load } = usePageLoader()
const statuses = ref([])
const categories = ref([])
const masterdataGroups = ref([])
const statusLightbox = ref(null)
const categoryLightbox = ref(null)
const masterdataGroupLightbox = ref(null)
const masterdataEntryLightbox = ref(null)
const { collapsed, toggle } = useCollapsed('settings')
const { confirm } = useConfirm()
const { canCreate, canUpdate, canDelete, canReorder } = useCan()

async function fetch() {
	const [statusesRes, categoriesRes, masterdataRes] = await Promise.all([
		statusesApi.index(),
		categoriesApi.index(),
		masterdataApi.index(),
	])
	statuses.value = statusesRes.data.data
	categories.value = categoriesRes.data.data
	masterdataGroups.value = masterdataRes.data.data
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

// Masterdata

async function onMasterdataGroupStored() {
	await fetch()
	masterdataGroups.value.unshift(masterdataGroups.value.pop())
	await reorderMasterdataGroups()
}

async function reorderMasterdataGroups() {
	const items = masterdataGroups.value.map((g, i) => ({
		uuid: g.section.uuid,
		sort_order: i,
	}))
	await masterdataGroupsApi.reorder(items)
}

async function reorderMasterdata(group) {
	const items = group.entries.map((e, i) => ({
		uuid: e.uuid,
		sort_order: i,
		masterdata_group_id: group.section.id,
	}))
	await masterdataApi.reorder(items)
}

async function destroyMasterdataGroup(group) {
	const count = group.entries.length
	const message = count
		? `Möchtest Du die Gruppe «${group.section.title}» wirklich löschen? Alle ${count} Einträge werden ebenfalls gelöscht.`
		: `Möchtest Du die Gruppe «${group.section.title}» wirklich löschen?`
	const ok = await confirm({
		message,
		confirmLabel: 'Löschen',
		variant: 'danger',
	})
	if (ok) {
		await masterdataGroupsApi.destroy(group.section.uuid)
		await fetch()
	}
}

async function toggleDefault(entry) {
	await masterdataApi.toggleStandard(entry.uuid)
	entry.standard = !entry.standard
}

async function destroyMasterdata(entry) {
	const ok = await confirm({
		message: 'Möchtest Du diesen Eintrag wirklich löschen?',
		confirmLabel: 'Löschen',
		variant: 'danger',
	})
	if (ok) {
		await masterdataApi.destroy(entry.uuid)
		await fetch()
	}
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
							:disabled="!canReorder"
							@change="reorderStatuses">
							<template #item="{ element: status }">
								<DraggableEntryRow
									:label="status.title"
									:show-publish="false"
									:editable="canUpdate"
									:draggable="canReorder"
									:deletable="canDelete"
									drag-handle-class="status-drag-handle"
									@edit="statusLightbox.edit(status)"
									@delete="destroyStatus(status)" />
							</template>
						</draggable>

						<Grid :cols="10" class="mb-10">
							<Span class="col-span-8 col-start-2">
								<NewEntryButton v-if="canCreate" @click="statusLightbox.open()" />
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
							:disabled="!canReorder"
							@change="reorderCategories">
							<template #item="{ element: category }">
								<DraggableEntryRow
									:label="category.title"
									:show-publish="false"
									:editable="canUpdate"
									:draggable="canReorder"
									:deletable="canDelete"
									drag-handle-class="category-drag-handle"
									@edit="categoryLightbox.edit(category)"
									@delete="destroyCategory(category)" />
							</template>
						</draggable>

						<Grid :cols="10" class="mb-10">
							<Span class="col-span-8 col-start-2">
								<NewEntryButton v-if="canCreate" @click="categoryLightbox.open()" />
							</Span>
						</Grid>
					</Span>

				</Grid>

			</Span>

			<!-- Stammdaten -->
			<Span class="col-span-10">

				<Grid :cols="10">

					<Span class="col-span-8 col-start-2">
						<CollapsibleHeader
							title="Stammdaten"
							:collapsed="collapsed.has('masterdata')"
							@toggle="toggle('masterdata')" />
					</Span>

					<Span v-show="!collapsed.has('masterdata')" class="col-span-10 col-start-1">

						<Grid :cols="10" class="mb-20">
							<Span class="col-span-8 col-start-2">
								<Button v-if="canCreate" @click="masterdataGroupLightbox.open()" class="px-20">
									<template #icon-right>
										<Plus class="w-10 h-10" />
									</template>
									Neue Gruppe
								</Button>
							</Span>
						</Grid>

						<draggable
							v-model="masterdataGroups"
							item-key="section.uuid"
							handle=".masterdata-group-drag-handle"
							ghost-class="opacity-50"
							animation="150"
							class="flex flex-col gap-20 min-h-1"
							:disabled="!canReorder"
							@end="reorderMasterdataGroups">

							<template #item="{ element: group }">

								<Span class="col-span-10">

									<Grid :cols="10">

										<!-- Group header -->
										<Span class="col-span-1 flex items-center justify-end">
											<Burger v-if="canReorder" class="w-18 h-10 cursor-grab masterdata-group-drag-handle" />
										</Span>

										<Span class="col-span-8">
											<CollapsibleHeader
												:title="group.section.title"
												:collapsed="collapsed.has(`md-${group.section.uuid}`)"
												:editable="canUpdate"
												@toggle="toggle(`md-${group.section.uuid}`)"
												@edit="masterdataGroupLightbox.edit(group.section)" />
										</Span>

										<Span class="col-span-1 flex items-center justify-start">
											<Cross v-if="canDelete" class="w-10 cursor-pointer" @click="destroyMasterdataGroup(group)" />
										</Span>

										<!-- Entries -->
										<Span v-show="!collapsed.has(`md-${group.section.uuid}`)" class="col-span-10 col-start-1">
											<draggable
												v-model="group.entries"
												group="masterdata-entries"
												item-key="uuid"
												handle=".masterdata-entry-drag-handle"
												ghost-class="opacity-50"
												animation="150"
												class="flex flex-col gap-10 min-h-1"
												:class="{ 'mb-10': group.entries.length }"
												:disabled="!canReorder"
												@change="reorderMasterdata(group)">
												<template #item="{ element: entry }">
													<DraggableEntryRow
														:label="entry.title"
														:split="true"
														:show-publish="false"
														:show-default="canUpdate"
														:standard="entry.standard"
														:editable="canUpdate"
														:draggable="canReorder"
														:deletable="canDelete"
														drag-handle-class="masterdata-entry-drag-handle"
														@edit="masterdataEntryLightbox.edit(entry, group.section.id)"
														@toggle-default="toggleDefault(entry)"
														@delete="destroyMasterdata(entry)" />
												</template>
											</draggable>

											<Grid :cols="10" class="mb-10">
												<Span class="col-span-8 col-start-2">
													<NewEntryButton v-if="canCreate" @click="masterdataEntryLightbox.open(group.section.id)" />
												</Span>
											</Grid>

										</Span>

									</Grid>

								</Span>

							</template>

						</draggable>

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

	<SectionTitleForm
		ref="masterdataGroupLightbox"
		label="Gruppe"
		create-label="Neue Gruppe"
		:store-fn="masterdataGroupsApi.store"
		:update-fn="masterdataGroupsApi.update"
		@stored="onMasterdataGroupStored"
		@updated="fetch" />

	<MasterdataEntryForm
		ref="masterdataEntryLightbox"
		:store-fn="masterdataApi.store"
		:update-fn="masterdataApi.update"
		@stored="fetch"
		@updated="fetch" />

</template>
