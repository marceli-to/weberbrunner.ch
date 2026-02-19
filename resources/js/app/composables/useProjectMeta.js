import { ref, computed } from 'vue'
import { useRoute } from 'vue-router'
import { useProject } from '@/composables/useProject'
import { useFormErrors } from '@/composables/useFormErrors'
import projectsApi from '@/api/projects'
import mediaApi from '@/api/media'

export function useProjectMeta() {
	const route = useRoute()
	const selectedOgImage = ref(null)
	const ogDrawerOpen = ref(false)

	const { project, fetch } = useProject((data) => {
		const ogMedia = data.media?.find(m => m.is_og)
		selectedOgImage.value = ogMedia?.uuid || null
	})

	const { submit } = useFormErrors()
	const ogImage = computed(() => project.value?.media?.find(m => m.is_og) || null)

	async function saveDescription() {
		const ok = await submit(() => projectsApi.update(route.params.id, {
			meta_description: project.value.meta_description,
		}))
		if (ok) await fetch()
	}

	async function saveOgImage() {
		if (!selectedOgImage.value) return
		await mediaApi.og(selectedOgImage.value)
		await fetch()
		ogDrawerOpen.value = false
	}

	async function removeOgImage() {
		if (!ogImage.value) return
		await mediaApi.update(ogImage.value.uuid, { is_og: false })
		selectedOgImage.value = null
		await fetch()
	}

	return {
		project,
		ogImage,
		selectedOgImage,
		ogDrawerOpen,
		saveDescription,
		saveOgImage,
		removeOgImage,
	}
}
