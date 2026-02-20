<script setup>
import { ref, computed, watch } from 'vue'
import projectsApi from '@/api/projects'
import Button from '@/components/ui/form/Button.vue'
import Checkbox from '@/components/ui/form/Checkbox.vue'
import Input from '@/components/ui/form/Input.vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'

const props = defineProps({
	mode: { type: String, default: 'external' },
	url: { type: String, default: '' },
	title: { type: String, default: '' },
	selectedProjectId: { type: [Number, String], default: null },
	titleOptional: { type: Boolean, default: false },
	newTab: { type: Boolean, default: false },
})

const localMode = ref(props.mode)
const localUrl = ref(props.url)
const localTitle = ref(props.title)
const localNewTab = ref(props.newTab)
const search = ref('')
const projects = ref([])
const selectedProject = ref(null)
const loading = ref(false)
const errors = ref({})

function validate() {
	errors.value = {}
	if (!props.titleOptional && !localTitle.value.trim()) {
		errors.value.title = 'Titel ist erforderlich'
	}
	if (localMode.value === 'external' && !localUrl.value.trim()) {
		errors.value.url = 'URL ist erforderlich'
	}
	if (localMode.value === 'internal' && !selectedProject.value) {
		errors.value.project = 'Projekt ist erforderlich'
	}
	return Object.keys(errors.value).length === 0
}

const filteredProjects = computed(() => {
	if (!search.value) return projects.value
	const q = search.value.toLowerCase()
	return projects.value.filter(p => p.title.toLowerCase().includes(q))
})

async function loadProjects() {
	if (projects.value.length) return
	loading.value = true
	try {
		const { data } = await projectsApi.index()
		projects.value = data.data || data
	} catch {
		projects.value = []
	}
	loading.value = false
}

function init() {
	localMode.value = props.mode || 'external'
	localUrl.value = props.url || ''
	localTitle.value = props.title || ''
	localNewTab.value = props.newTab || false
	search.value = ''
	selectedProject.value = null
	errors.value = {}
	loadProjects().then(() => {
		if (props.selectedProjectId) {
			selectedProject.value = projects.value.find(p => p.id === props.selectedProjectId) || null
		}
	})
}

watch(() => [props.mode, props.url, props.title, props.selectedProjectId], init, { immediate: true })

function selectProject(project) {
	selectedProject.value = project
	search.value = ''
}

function setMode(mode) {
	localMode.value = mode
	if (mode === 'internal') {
		localNewTab.value = false
	}
}

defineExpose({
	validate,
	getFormData() {
		return {
			mode: localMode.value,
			url: localUrl.value,
			title: localTitle.value,
			selectedProject: selectedProject.value,
			newTab: localNewTab.value,
		}
	},
	setSelectedProject(project) {
		selectedProject.value = project
	},
	get projects() {
		return projects.value
	},
})
</script>

<template>
	<Grid :cols="2">

		<!-- Mode tabs -->
		<Span class="col-span-1">
			<Button 
        class="justify-center" 
        :class="localMode === 'internal' ? '!bg-navy !border-navy !text-white' : ''" 
        @click="setMode('internal')">
        Projekt
      </Button>
		</Span>
		<Span class="col-span-1">
			<Button 
        class="justify-center" 
        :class="localMode === 'external' ? '!bg-navy !border-navy !text-white' : ''" 
        @click="setMode('external')">
        Extern
      </Button>
		</Span>

		<!-- Projekt mode -->
		<Span v-if="localMode === 'internal'" class="col-span-2">
			<div v-if="selectedProject" class="flex items-center gap-8">
				<span class="text-md font-semibold">{{ selectedProject.title }}</span>
				<button
					type="button"
					class="text-xs text-gray hover:text-black underline"
					@click="selectedProject = null">
					Ändern
				</button>
			</div>
			<div v-else>
				<Input v-model="search" placeholder="Projekt suchen..." :error="errors.project" @focus="errors.project = null" />
				<div v-if="loading" class="text-xs text-gray mt-4">Laden...</div>
				<ul v-else class="max-h-160 overflow-y-auto border-thin border-solid border-black mt-4">
					<li
						v-for="project in filteredProjects"
						:key="project.uuid"
						class="px-10 py-6 text-xs cursor-pointer hover:bg-snow"
						@click="selectProject(project)">
						{{ project.title }}
					</li>
					<li v-if="!filteredProjects.length" class="px-10 py-6 text-xs text-gray">
						Keine Projekte gefunden
					</li>
				</ul>
			</div>
		</Span>

		<!-- Extern mode -->
		<Span v-if="localMode === 'external'" class="col-span-2">
			<Input v-model="localUrl" type="url" placeholder="https://..." :error="errors.url" @focus="errors.url = null" />
		</Span>

		<!-- Title -->
		<Span class="col-span-2">
			<Input v-model="localTitle" :placeholder="titleOptional ? 'Titel (optional)' : 'Titel'" :error="errors.title" @focus="errors.title = null" />
		</Span>

		<!-- New tab checkbox -->
		<Span class="col-span-2">
			<Checkbox v-model="localNewTab" label="In neuem Fenster öffnen" />
		</Span>

	</Grid>
</template>
