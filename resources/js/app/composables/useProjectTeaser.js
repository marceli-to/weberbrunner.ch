import { ref, computed } from 'vue'
import { useRoute } from 'vue-router'
import { useProject } from '@/composables/useProject'
import { useToast } from '@/composables/useToast'
import mediaApi from '@/api/media'

export function useProjectTeaser() {
	const route = useRoute()
	const selectedTeaserImage = ref(null)
	const teaserDrawerOpen = ref(false)

	const { project, fetch } = useProject((data) => {
		const teaserMedia = data.media?.find(m => m.is_teaser)
		selectedTeaserImage.value = teaserMedia?.uuid || null
	}, { skipFetch: true })

	const toast = useToast()
	const teaserImage = computed(() => project.value?.media?.find(m => m.is_teaser) || null)

	async function saveTeaserImage() {
		if (!selectedTeaserImage.value) return
		await mediaApi.teaser(selectedTeaserImage.value)
		await fetch()
		teaserDrawerOpen.value = false
		toast.success('Gespeichert')
	}

	async function removeTeaserImage() {
		if (!teaserImage.value) return
		await mediaApi.update(teaserImage.value.uuid, { is_teaser: false })
		selectedTeaserImage.value = null
		await fetch()
	}

	return {
		project,
		teaserImage,
		selectedTeaserImage,
		teaserDrawerOpen,
		saveTeaserImage,
		removeTeaserImage,
	}
}
