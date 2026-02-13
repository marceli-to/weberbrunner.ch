import { ref } from 'vue'

export function useLightbox(onOpen) {
	const show = ref(false)

	function open() {
		onOpen?.()
		show.value = true
	}

	function close() {
		show.value = false
	}

	return { show, open, close }
}
