<script setup>
import { ref, watch, computed } from 'vue'
import AppDialog from '@/components/ui/dialog/AppDialog.vue'
import Button from '@/components/ui/form/Button.vue'
import projectsApi from '@/api/projects'

const props = defineProps({
	open: { type: Boolean, default: false },
	editor: { type: Object, required: true },
})

const emit = defineEmits(['close'])

const mode = ref('extern')
const url = ref('')
const title = ref('')
const newTab = ref(false)
const search = ref('')
const projects = ref([])
const selectedProject = ref(null)
const loading = ref(false)

const filteredProjects = computed(() => {
	if (!search.value) return projects.value
	const q = search.value.toLowerCase()
	return projects.value.filter(p => p.title.toLowerCase().includes(q))
})

watch(() => props.open, async (val) => {
	if (!val) return

	mode.value = 'extern'
	url.value = ''
	title.value = ''
	newTab.value = false
	search.value = ''
	selectedProject.value = null

	const attrs = props.editor.getAttributes('link')
	if (attrs.href) {
		url.value = attrs.href
		title.value = attrs.title || ''
		newTab.value = attrs.target === '_blank'
	}

	loading.value = true
	try {
		const { data } = await projectsApi.index()
		projects.value = data.data || data
	} catch {
		projects.value = []
	}
	loading.value = false

	if (attrs.href) {
		const match = projects.value.find(p => attrs.href === `/arbeiten/${p.slug}`)
		if (match) {
			mode.value = 'projekt'
			selectedProject.value = match
			url.value = attrs.href
		}
	}
})

function selectProject(project) {
	selectedProject.value = project
	url.value = `/arbeiten/${project.slug}`
	search.value = ''
}

function apply() {
	const href = url.value.trim()
	if (!href) return

	const attrs = { href }
	if (title.value.trim()) attrs.title = title.value.trim()
	attrs.target = newTab.value ? '_blank' : null

	props.editor.chain().focus().extendMarkRange('link').setLink(attrs).run()
	emit('close')
}

function remove() {
	props.editor.chain().focus().extendMarkRange('link').unsetLink().run()
	emit('close')
}
</script>

<template>
	<AppDialog :open="open" title="Link" @close="emit('close')">

		<!-- Mode tabs -->
		<div class="flex gap-4 mb-16">
			<button
				type="button"
				class="px-10 py-4 text-xs font-semibold border"
				:class="mode === 'projekt' ? 'bg-black text-white border-black' : 'bg-white text-black border-silver hover:border-black'"
				@click="mode = 'projekt'; newTab = false">
				Projekt
			</button>
			<button
				type="button"
				class="px-10 py-4 text-xs font-semibold border"
				:class="mode === 'extern' ? 'bg-black text-white border-black' : 'bg-white text-black border-silver hover:border-black'"
				@click="mode = 'extern'; newTab = true">
				Extern
			</button>
		</div>

		<!-- Projekt mode -->
		<div v-if="mode === 'projekt'" class="flex flex-col gap-20">
			<div>
				<div v-if="selectedProject" class="flex items-center gap-8">
					<span class="text-md font-semibold">{{ selectedProject.title }}</span>
					<button
						type="button"
						class="text-xs text-gray hover:text-black underline"
						@click="selectedProject = null; url = ''">
						Ändern
					</button>
				</div>
				<div v-else>
					<input
						v-model="search"
						type="text"
						placeholder="Projekt suchen..."
						class="form-input form-input--sm w-full"
					/>
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
			</div>
		</div>

		<!-- Extern mode -->
		<div v-if="mode === 'extern'" class="flex flex-col gap-20">
			<div>
				<input
					v-model="url"
					type="url"
					placeholder="https://..."
					class="form-input form-input--sm w-full"
				/>
			</div>
		</div>

		<!-- Shared fields -->
		<div class="flex flex-col gap-20 mt-20">
			<div>
				<input
					v-model="title"
					type="text"
					placeholder="Titel (optional)"
					class="form-input form-input--sm w-full"
				/>
			</div>
			<label class="flex items-center gap-6 text-xs">
				<input
					v-model="newTab"
					type="checkbox"
				/>
				<span>In neuem Tab öffnen</span>
			</label>
		</div>

		<template #footer>
			<div class="flex gap-20">
				<Button class="flex justify-center" @click="apply">Übernehmen</Button>
				<Button v-if="editor.isActive('link')" class="flex justify-center" @click="remove">Entfernen</Button>
			</div>
		</template>

	</AppDialog>
</template>
