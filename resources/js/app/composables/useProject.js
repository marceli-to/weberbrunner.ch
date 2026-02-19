import { ref } from 'vue'
import { useRoute } from 'vue-router'
import projectsApi from '@/api/projects'
import { usePageLoader } from '@/composables/usePageLoader'

export function useProject(onFetched) {
	const route = useRoute()
	const { load } = usePageLoader()
	const project = ref(null)

	async function fetch() {
		const { data } = await projectsApi.show(route.params.id)
		project.value = data.data
		if (onFetched) onFetched(data.data)
	}

	load(fetch)

	return { project, fetch }
}
