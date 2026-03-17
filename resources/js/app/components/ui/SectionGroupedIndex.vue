<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import sectionsApi from '@/api/sections'
import { usePageLoader } from '@/composables/usePageLoader'
import { useCollapsed } from '@/composables/useCollapsed'
import { useConfirm } from '@/composables/useConfirm'
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

const props = defineProps({
	pageTitle: String,
	api: Object,
	sectionType: String,
	routePrefix: String,
	collapsedKey: String,
	labelField: {
		type: String,
		default: 'text_plain',
	},
})

const router = useRouter()
const { load } = usePageLoader()
const groups = ref([])
const { collapsed, toggle: toggleSection } = useCollapsed(props.collapsedKey)
const { confirm } = useConfirm()
const lightbox = ref(null)

async function fetch() {
	const { data } = await props.api.index()
	groups.value = data.data
}

async function onGroupStored() {
	await fetch()
	groups.value.unshift(groups.value.pop())
	await reorderGroups()
}

function storeGroupFn(title) {
	return sectionsApi.store({ title, type: props.sectionType })
}

function updateGroupFn(uuid, title) {
	return sectionsApi.update(uuid, { title })
}

async function destroySection(group) {
	const count = group.entries.length
	const message = count
		? `Möchtest Du die Kategorie «${group.section.title}» wirklich löschen? Alle ${count} Einträge werden ebenfalls gelöscht.`
		: `Möchtest Du die Kategorie «${group.section.title}» wirklich löschen?`
	const ok = await confirm({
		message,
		confirmLabel: 'Löschen',
		variant: 'danger',
	})
	if (ok) {
		await sectionsApi.destroy(group.section.uuid)
		await fetch()
	}
}

async function destroy(entry) {
	const ok = await confirm({
		message: 'Möchtest Du diesen Eintrag wirklich löschen?',
		confirmLabel: 'Löschen',
		variant: 'danger',
	})
	if (ok) {
		await props.api.destroy(entry.uuid)
		await fetch()
	}
}

async function toggle(entry) {
	entry.publish = !entry.publish
	await props.api.toggle(entry.uuid)
}

async function reorderGroups() {
	const items = groups.value.map((g, i) => ({
		uuid: g.section.uuid,
		sort_order: i,
	}))
	await sectionsApi.reorder(items)
}

async function reorder(group) {
	const items = group.entries.map((e, i) => ({
		uuid: e.uuid,
		sort_order: i,
		section_id: group.section.id,
	}))
	await props.api.reorder(items)
}

load(fetch)
</script>

<template>

	<!-- Header -->
	<Grid class="mb-40">

		<Span class="col-span-8 col-start-2">
			<PageTitle>{{ pageTitle }}</PageTitle>
		</Span>

		<Span class="col-span-8 col-start-2">

			<Button @click="lightbox.open()" class="px-20">
				<template #icon-right>
					<Plus class="w-10 h-10" />
				</template>
				Neue Kategorie
			</Button>

		</Span>

	</Grid>

	<!-- Entries -->
	<Grid>

		<draggable
			v-model="groups"
			item-key="section.uuid"
			handle=".section-drag-handle"
			ghost-class="opacity-50"
			animation="150"
			class="col-span-10 flex flex-col gap-20"
			@end="reorderGroups">

			<template #item="{ element: group }">

				<Span class="col-span-10">

					<Grid :cols="10">

						<!-- Section header -->
						<Span class="col-span-1 flex items-center justify-end">
							<Burger class="w-18 h-10 cursor-grab section-drag-handle" />
						</Span>

						<Span class="col-span-8">
							<CollapsibleHeader
								:title="group.section.title"
								:collapsed="collapsed.has(group.section.uuid)"
								editable
								@toggle="toggleSection(group.section.uuid)"
								@edit="lightbox.edit(group.section)" />
						</Span>

						<Span class="col-span-1 flex items-center justify-start">
							<Cross class="w-10 cursor-pointer" @click="destroySection(group)" />
						</Span>

						<!-- Entries -->
						<Span v-show="!collapsed.has(group.section.uuid)" class="col-span-10 col-start-1">
							<draggable
								v-model="group.entries"
								group="entries"
								item-key="uuid"
								handle=".entry-drag-handle"
								ghost-class="opacity-50"
								animation="150"
								class="flex flex-col gap-10 min-h-1"
								:class="{ 'mb-10': group.entries.length }"
								@change="reorder(group)">
								<template #item="{ element: entry }">
									<DraggableEntryRow
										:label="entry[labelField]"
										:publish="entry.publish"
										drag-handle-class="entry-drag-handle"
										@edit="router.push({ name: `${routePrefix}.edit`, params: { id: entry.uuid } })"
										@toggle-publish="toggle(entry)"
										@delete="destroy(entry)" />
								</template>
							</draggable>

							<Grid :cols="10" class="mb-10">
								<Span class="col-span-8 col-start-2">
									<NewEntryButton @click="router.push({ name: `${routePrefix}.create`, query: { section: group.section.uuid } })" />
								</Span>
							</Grid>

						</Span>

					</Grid>

				</Span>

			</template>

		</draggable>

	</Grid>

	<!-- Lightbox -->
	<SectionTitleForm
		ref="lightbox"
		label="Kategorie"
		create-label="Neue Kategorie"
		:store-fn="storeGroupFn"
		:update-fn="updateGroupFn"
		@stored="onGroupStored"
		@updated="fetch" />

</template>
