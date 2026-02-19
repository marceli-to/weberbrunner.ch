import { ref } from 'vue'
import { useRoute } from 'vue-router'
import projectsApi from '@/api/projects'
import statusesApi from '@/api/statuses'
import categoriesApi from '@/api/categories'
import { useProject } from '@/composables/useProject'
import { useToast } from '@/composables/useToast'


export function useProjectSettings() {
	const route = useRoute()
	const toast = useToast()

	const statuses = ref([])
	const categories = ref([])

	const { project } = useProject(null, { skipFetch: true })

	async function loadOptions() {
		const [statusesRes, categoriesRes] = await Promise.all([
			statusesApi.index(),
			categoriesApi.index(),
		])
		statuses.value = statusesRes.data.data
		categories.value = categoriesRes.data.data
	}

	loadOptions()

	function isStatusSelected(id) {
		return project.value?.statuses?.some(s => s.id === id) || false
	}

	function isCategorySelected(id) {
		return project.value?.categories?.some(c => c.id === id) || false
	}

	async function togglePublish(value) {
		const previous = project.value.publish
		project.value.publish = value
		try {
			await projectsApi.toggle(route.params.id)
		} catch {
			project.value.publish = previous
			toast.error('Fehler beim Speichern')
		}
	}

	async function toggleStatus(id) {
		const previous = [...project.value.statuses]
		if (isStatusSelected(id)) {
			project.value.statuses = project.value.statuses.filter(s => s.id !== id)
		} else {
			const status = statuses.value.find(s => s.id === id)
			project.value.statuses = [...project.value.statuses, status]
		}
		try {
			await projectsApi.syncStatuses(route.params.id, project.value.statuses.map(s => s.id))
		} catch {
			project.value.statuses = previous
			toast.error('Fehler beim Speichern')
		}
	}

	async function toggleCategory(id) {
		const previous = [...project.value.categories]
		if (isCategorySelected(id)) {
			project.value.categories = project.value.categories.filter(c => c.id !== id)
		} else {
			const category = categories.value.find(c => c.id === id)
			project.value.categories = [...project.value.categories, category]
		}
		try {
			await projectsApi.syncCategories(route.params.id, project.value.categories.map(c => c.id))
		} catch {
			project.value.categories = previous
			toast.error('Fehler beim Speichern')
		}
	}

	return {
		project,
		statuses,
		categories,
		isStatusSelected,
		isCategorySelected,
		togglePublish,
		toggleStatus,
		toggleCategory,
	}
}
