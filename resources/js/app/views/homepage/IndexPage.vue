<script setup>
import { ref, computed } from 'vue'
import homepageApi from '@/api/homepage'
import projectsApi from '@/api/projects'
import { usePageLoader } from '@/composables/usePageLoader'
import { useConfirm } from '@/composables/useConfirm'
import draggable from 'vuedraggable'
import PageTitle from '@/components/ui/PageTitle.vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import Button from '@/components/ui/form/Button.vue'
import Burger from '@/components/icons/Burger.vue'
import Cross from '@/components/icons/Cross.vue'
import Plus from '@/components/icons/Plus.vue'
import Drawer from '@/components/ui/drawer/Drawer.vue'

const { load } = usePageLoader()
const { confirm } = useConfirm()

const columns = ref({ 1: [], 2: [], 3: [] })
const allProjects = ref([])
const drawerOpen = ref(false)
const drawerColumn = ref(null)

const placedProjectIds = computed(() => {
	const ids = new Set()
	for (const col of Object.values(columns.value)) {
		for (const item of col) {
			ids.add(item.project_id)
		}
	}
	return ids
})

const availableProjects = computed(() => {
	return allProjects.value.filter(p => !placedProjectIds.value.has(p.id))
})

async function fetch() {
	const [homepageRes, projectsRes] = await Promise.all([
		homepageApi.index(),
		projectsApi.index(),
	])
	columns.value = homepageRes.data.data
	allProjects.value = projectsRes.data.data
}

function openDrawer(col) {
	drawerColumn.value = col
	drawerOpen.value = true
}

async function addProject(project) {
	drawerOpen.value = false
	const { data } = await homepageApi.store({
		project_id: project.id,
		column: drawerColumn.value,
	})
	columns.value[drawerColumn.value].push(data.data)
}

async function removeItem(item, col) {
	const ok = await confirm({
		message: `«${item.project?.full_title || item.project?.title}» von der Startseite entfernen?`,
		confirmLabel: 'Entfernen',
		variant: 'danger',
	})
	if (!ok) return

	await homepageApi.destroy(item.uuid)
	columns.value[col] = columns.value[col].filter(i => i.uuid !== item.uuid)
}

async function onDragEnd() {
	const items = []
	for (const col of [1, 2, 3]) {
		columns.value[col].forEach((item, index) => {
			items.push({
				uuid: item.uuid,
				column: col,
				sort_order: index,
			})
		})
	}
	await homepageApi.reorder(items)
}

load(fetch)
</script>

<template>

	<Grid class="mb-40">
		<Span class="col-span-8 col-start-2">
			<PageTitle>Startseite</PageTitle>
		</Span>
	</Grid>

	<Grid>
		<Span class="col-span-8 col-start-2">
			<Grid :cols="3">

				<div v-for="col in [1, 2, 3]" :key="col" class="flex flex-col">

					<div class="text-xs font-semibold mb-10">Spalte {{ col }}</div>

					<draggable
						v-model="columns[col]"
						group="homepage"
						item-key="uuid"
						handle=".homepage-drag-handle"
						ghost-class="opacity-50"
						animation="150"
						class="flex flex-col gap-10 min-h-40"
						@end="onDragEnd">
						<template #item="{ element: item }">
							<div class="bg-white border-thin border-black flex items-center justify-between px-10 min-h-30 select-none gap-10">
								<Burger variant="sm" class="w-18 h-10 cursor-grab homepage-drag-handle shrink-0" />
								<span class="text-sm truncate flex-1">{{ item.project?.full_title || item.project?.title }}</span>
								<Cross class="w-10 cursor-pointer shrink-0" @click="removeItem(item, col)" />
							</div>
						</template>
					</draggable>

					<Button class="px-10 mt-10" @click="openDrawer(col)">
						<template #icon-right>
							<Plus class="w-10 h-10" />
						</template>
						Projekt
					</Button>

				</div>

			</Grid>
		</Span>
	</Grid>

	<!-- Project picker drawer -->
	<Drawer
		:open="drawerOpen"
		cancel-label="Abbrechen"
		@close="drawerOpen = false">

		<Grid :cols="6" class="mt-40">
			<Span class="col-span-4 col-start-2">
				<div class="text-white text-sm mb-20">Projekt wählen (Spalte {{ drawerColumn }})</div>
				<div class="flex flex-col gap-10">
					<button
						v-for="project in availableProjects"
						:key="project.uuid"
						type="button"
						class="flex items-center gap-x-10 border-t-thin border-t-white pt-10 cursor-pointer w-full text-left"
						@click="addProject(project)">
						<Plus class="w-10 h-10 text-white shrink-0" />
						<span class="text-white text-sm truncate">{{ project.full_title || project.title }}</span>
					</button>
				</div>
			</Span>
		</Grid>

	</Drawer>

</template>
