<script setup>
import { ref, computed } from 'vue'
import landingApi from '@/api/landing'
import pageTextApi from '@/api/pageText'
import projectsApi from '@/api/projects'
import { usePageLoader } from '@/composables/usePageLoader'
import { useCollapsed } from '@/composables/useCollapsed'
import { useConfirm } from '@/composables/useConfirm'
import { useFormErrors } from '@/composables/useFormErrors'
import { useToast } from '@/composables/useToast'
import draggable from 'vuedraggable'
import PageTitle from '@/components/ui/PageTitle.vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import Card from '@/components/ui/Card.vue'
import CollapsibleHeader from '@/components/ui/CollapsibleHeader.vue'
import Button from '@/components/ui/form/Button.vue'
import Textarea from '@/components/ui/form/Textarea.vue'
import Plus from '@/components/icons/Plus.vue'
import LandingCard from '@/components/landing/LandingCard.vue'
import ProjectPickerDrawer from '@/components/landing/ProjectPickerDrawer.vue'

const { load } = usePageLoader()
const { collapsed, toggle } = useCollapsed('landing')
const { confirm } = useConfirm()
const { submit } = useFormErrors()
const toast = useToast()

const columns = ref({ 1: [], 2: [], 3: [] })
const allProjects = ref([])
const drawerOpen = ref(false)
const drawerColumn = ref(null)
const selectedProjectUuid = ref(null)

const landingText = ref({ text: '' })
const originalText = ref('')

const textDirty = computed(() => landingText.value.text !== originalText.value)

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
	const [landingRes, projectsRes, textRes] = await Promise.all([
		landingApi.index(),
		projectsApi.published(),
		pageTextApi.show('landing'),
	])
	columns.value = landingRes.data.data
	allProjects.value = projectsRes.data.data
	landingText.value = { text: textRes.data.data.text ?? '' }
	originalText.value = textRes.data.data.text ?? ''
}

function openDrawer(col) {
	drawerColumn.value = col
	selectedProjectUuid.value = null
	drawerOpen.value = true
}

function closeDrawer() {
	drawerOpen.value = false
	drawerColumn.value = null
	selectedProjectUuid.value = null
}

async function addProject() {
	if (!selectedProjectUuid.value) return
	const project = allProjects.value.find(p => p.uuid === selectedProjectUuid.value)
	if (!project) return
	const col = drawerColumn.value
	drawerOpen.value = false
	const { data } = await landingApi.store({
		project_id: project.id,
		column: col,
	})
	columns.value[col].push(data.data)
}

async function removeItem(item, col) {
	const ok = await confirm({
		message: `«${item.project?.full_title || item.project?.title}» von der Startseite entfernen?`,
		confirmLabel: 'Entfernen',
		variant: 'danger',
	})
	if (!ok) return

	await landingApi.destroy(item.uuid)
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
	await landingApi.reorder(items)
}

async function saveText() {
	const ok = await submit(() => pageTextApi.update('landing', {
		text: landingText.value.text,
	}))
	if (!ok) return
	originalText.value = landingText.value.text
	toast.success('Gespeichert')
}

function cancelText() {
	landingText.value.text = originalText.value
}

load(fetch)
</script>

<template>

	<Grid class="mb-40">
		<Span class="col-span-8 col-start-2">
			<PageTitle>Startseite</PageTitle>
		</Span>
	</Grid>

	<!-- Intro-Text -->
	<Grid class="mb-20">
		<Span class="col-span-8 col-start-2">
			<CollapsibleHeader
				title="Intro"
				:collapsed="collapsed.has('intro-text')"
				@toggle="toggle('intro-text')" />
		</Span>
		<Span v-show="!collapsed.has('intro-text')" class="col-span-8 col-start-2">
			<Card>
				<form @submit.prevent="saveText">
					<Textarea v-model="landingText.text" :rows="8" />
					<div class="flex gap-20 mt-10">
						<Button type="submit" class="flex justify-center" :disabled="!textDirty">Speichern</Button>
						<Button type="button" class="flex justify-center" :disabled="!textDirty" @click="cancelText">Abbrechen</Button>
					</div>
				</form>
			</Card>
		</Span>
	</Grid>

	<!-- Layout -->
	<Grid class="mb-20">
		<Span class="col-span-8 col-start-2">
			<CollapsibleHeader
				title="Layout"
				:collapsed="collapsed.has('layout')"
				@toggle="toggle('layout')" />
		</Span>
		<Span v-show="!collapsed.has('layout')" class="col-span-8 col-start-2">
			<Grid :cols="3">

				<div v-for="col in [1, 2, 3]" :key="col" class="flex flex-col">

					<div class="text-xs font-semibold mb-10">
            Spalte {{ col }}
          </div>

					<draggable
						v-if="columns[col].length"
						v-model="columns[col]"
						group="landing"
						item-key="uuid"
						handle=".landing-drag-handle"
						ghost-class="opacity-50"
						animation="150"
						class="flex flex-col gap-20 min-h-40"
						@end="onDragEnd">
						<template #item="{ element: item }">
							<LandingCard :item="item" @delete="removeItem(item, col)" />
						</template>
					</draggable>

					<Button
            class="px-10 mt-20"
            @click="openDrawer(col)">
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
	<ProjectPickerDrawer
		:open="drawerOpen"
		:items="availableProjects"
		v-model="selectedProjectUuid"
		@submit="addProject"
		@close="closeDrawer" />

</template>
