import { ref } from 'vue'
import { useRoute } from 'vue-router'
import projectsApi from '@/api/projects'
import { usePageLoader } from '@/composables/usePageLoader'

let shared = { id: null, project: null }

export function useProject(onFetched, { skipFetch = false } = {}) {
	const route = useRoute()
	const id = route.params.id

	if (shared.id === id && shared.project) {
		const project = shared.project
		if (!skipFetch) {
			const { load } = usePageLoader()
			load(async () => {
				const { data } = await projectsApi.show(id)
				project.value = data.data
				if (onFetched) onFetched(data.data)
			})
		} else if (onFetched && project.value) {
			onFetched(project.value)
		}
		return { project, fetch: () => refetch(id, project, onFetched) }
	}

	const project = ref(null)
	shared = { id, project }

	const { load } = usePageLoader()
	load(async () => {
		const { data } = await projectsApi.show(id)
		project.value = data.data
		if (onFetched) onFetched(data.data)
	})

	return { project, fetch: () => refetch(id, project, onFetched) }
}

async function refetch(id, project, onFetched) {
	const { data } = await projectsApi.show(id)
	project.value = data.data
	if (onFetched) onFetched(data.data)
}
