<script setup>
import { ref, computed, watch } from 'vue'
import mediaApi from '@/api/media'
import projectsApi from '@/api/projects'
import MediaUploader from '@/components/media/MediaUploader.vue'
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
	selectedMediaUuid: { type: String, default: null },
	showMediaTab: { type: Boolean, default: false },
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
const mediaItems = ref([])
const mediaSearch = ref('')
const selectedMedia = ref(null)
const loading = ref(false)
const mediaLoading = ref(false)
const errors = ref({})
const fullSpanClass = computed(() => props.showMediaTab ? 'col-span-3' : 'col-span-2')

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
	if (localMode.value === 'media' && !selectedMedia.value) {
		errors.value.media = 'Datei ist erforderlich'
	}
	return Object.keys(errors.value).length === 0
}

const filteredProjects = computed(() => {
	if (!search.value) return projects.value
	const q = search.value.toLowerCase()
	return projects.value.filter(p => p.title.toLowerCase().includes(q))
})

const filteredMedia = computed(() => {
	if (!mediaSearch.value) return mediaItems.value
	const q = mediaSearch.value.toLowerCase()
	return mediaItems.value.filter(m => m.original_name?.toLowerCase().includes(q))
})

async function loadProjects() {
	if (projects.value.length) return
	loading.value = true
	try {
		const { data } = await projectsApi.published()
		projects.value = data.data || data
	} catch {
		projects.value = []
	}
	loading.value = false
}

async function loadMedia() {
	if (mediaItems.value.length) return
	mediaLoading.value = true
	try {
		const { data } = await mediaApi.index()
		mediaItems.value = data.data || data
	} catch {
		mediaItems.value = []
	}
	mediaLoading.value = false
}

function mediaTypeLabel(item) {
	if (item.mime_type?.includes('pdf')) return 'PDF'
	if (item.mime_type?.startsWith('image/')) return 'Bild'
	return 'Datei'
}

function init() {
	localMode.value = props.mode || 'external'
	localUrl.value = props.url || ''
	localTitle.value = props.title || ''
	localNewTab.value = props.newTab || false
	search.value = ''
	mediaSearch.value = ''
	selectedProject.value = null
	selectedMedia.value = null
	errors.value = {}
	loadProjects().then(() => {
		if (props.selectedProjectId) {
			selectedProject.value = projects.value.find(p => p.id === props.selectedProjectId) || null
		}
	})
	if (props.showMediaTab) {
		loadMedia().then(() => {
			if (props.selectedMediaUuid) {
				selectedMedia.value = mediaItems.value.find(m => m.uuid === props.selectedMediaUuid) || null
			}
		})
	}
}

watch(() => [props.mode, props.url, props.title, props.selectedProjectId, props.selectedMediaUuid], init, { immediate: true })

function selectProject(project) {
	selectedProject.value = project
	search.value = ''
}

function selectMedia(item) {
	selectedMedia.value = item
	mediaSearch.value = ''
}

async function onMediaUploaded(tempData) {
	try {
		const { data } = await mediaApi.persist(tempData)
		const persisted = data.data || data
		mediaItems.value.unshift(persisted)
		selectMedia(persisted)
	} catch {
		// fallback: use temp data directly
		mediaItems.value.unshift(tempData)
		selectMedia(tempData)
	}
}

function setMode(mode) {
	localMode.value = mode
	if (mode === 'internal') {
		localNewTab.value = false
	}
	if (mode === 'media') {
		localNewTab.value = true
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
			selectedMedia: selectedMedia.value,
			newTab: localNewTab.value,
		}
	},
	setSelectedProject(project) {
		selectedProject.value = project
	},
	setSelectedMedia(media) {
		selectedMedia.value = media
	},
	get projects() {
		return projects.value
	},
	get mediaItems() {
		return mediaItems.value
	},
})
</script>

<template>
	<Grid :cols="showMediaTab ? 3 : 2">

		<!-- Mode tabs -->
		<Span class="col-span-1">
			<Button
				class="justify-center"
				:variant="localMode === 'internal' ? 'active' : 'default'"
				@click="setMode('internal')">
				Projekt
			</Button>
		</Span>
		<Span class="col-span-1">
			<Button
				class="justify-center"
				:variant="localMode === 'external' ? 'active' : 'default'"
				@click="setMode('external')">
				Extern
			</Button>
		</Span>
		<Span v-if="showMediaTab" class="col-span-1">
			<Button
				class="justify-center"
				:variant="localMode === 'media' ? 'active' : 'default'"
				@click="setMode('media')">
				Datei
			</Button>
		</Span>

		<!-- Projekt mode -->
		<Span v-if="localMode === 'internal'" :class="fullSpanClass">
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
		<Span v-if="localMode === 'external'" :class="fullSpanClass">
			<Input v-model="localUrl" type="url" placeholder="https://..." :error="errors.url" @focus="errors.url = null" />
		</Span>

		<!-- Datei mode -->
		<Span v-if="localMode === 'media'" :class="fullSpanClass">
			<div v-if="selectedMedia" class="flex items-center gap-8">
				<span class="text-md font-semibold">{{ selectedMedia.original_name }}</span>
				<button
					type="button"
					class="text-xs text-gray hover:text-black underline"
					@click="selectedMedia = null">
					Ändern
				</button>
			</div>
			<div v-else>
				<Input v-model="mediaSearch" placeholder="Datei suchen..." :error="errors.media" @focus="errors.media = null" />
				<div v-if="mediaLoading" class="text-xs text-gray mt-4">Laden...</div>
				<ul v-else class="max-h-160 overflow-y-auto border-thin border-solid border-black mt-4">
					<li
						v-for="item in filteredMedia"
						:key="item.uuid"
						class="px-10 py-6 text-xs cursor-pointer hover:bg-snow flex items-center justify-between"
						@click="selectMedia(item)">
						<span>{{ item.original_name }}</span>
						<span class="text-gray uppercase">{{ mediaTypeLabel(item) }}</span>
					</li>
					<li v-if="!filteredMedia.length" class="px-10 py-6 text-xs text-gray">
						Keine Dateien gefunden
					</li>
				</ul>
				<MediaUploader
					class="mt-10"
					:allowed-file-types="['.jpg', '.jpeg', '.png', '.webp', '.gif', '.pdf']"
					@uploaded="onMediaUploaded" />
			</div>
		</Span>

		<!-- Title -->
		<Span :class="fullSpanClass">
			<Input v-model="localTitle" :placeholder="titleOptional ? 'Titel (optional)' : 'Titel'" :error="errors.title" @focus="errors.title = null" />
		</Span>

		<!-- New tab checkbox -->
		<Span :class="fullSpanClass">
			<Checkbox v-model="localNewTab" label="In neuem Fenster öffnen" />
		</Span>

	</Grid>
</template>
