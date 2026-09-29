import { ref, computed } from 'vue'

const pending = ref(0)
const isSaving = computed(() => pending.value > 0)

export function startSaving() {
	pending.value++
}

export function finishSaving() {
	pending.value = Math.max(0, pending.value - 1)
}

export function useSaving() {
	return { isSaving }
}
