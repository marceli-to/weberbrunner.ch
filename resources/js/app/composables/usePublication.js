import { ref } from 'vue'
import { useRoute } from 'vue-router'
import publicationsApi from '@/api/publications'
import { usePageLoader } from '@/composables/usePageLoader'

let shared = { id: null, publication: null }

export function usePublication(onFetched, { skipFetch = false } = {}) {
	const route = useRoute()
	const id = route.params.id

	if (shared.id === id && shared.publication) {
		const publication = shared.publication
		if (!skipFetch) {
			const { load } = usePageLoader()
			load(async () => {
				const { data } = await publicationsApi.show(id)
				publication.value = data.data
				if (onFetched) onFetched(data.data)
			})
		} else if (onFetched && publication.value) {
			onFetched(publication.value)
		}
		return { publication, fetch: () => refetch(id, publication, onFetched) }
	}

	const publication = ref(null)
	shared = { id, publication }

	const { load } = usePageLoader()
	load(async () => {
		const { data } = await publicationsApi.show(id)
		publication.value = data.data
		if (onFetched) onFetched(data.data)
	})

	return { publication, fetch: () => refetch(id, publication, onFetched) }
}

async function refetch(id, publication, onFetched) {
	const { data } = await publicationsApi.show(id)
	publication.value = data.data
	if (onFetched) onFetched(data.data)
}
