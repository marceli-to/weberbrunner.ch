import { reactive, readonly, toRef } from 'vue'

const state = reactive({
	current: null,
})

let nextId = 0

function add(type, message) {
	const id = nextId++
	state.current = { id, type, message }

	if (type === 'success') {
		setTimeout(() => dismiss(id), 2000)
	}
}

function dismiss(id) {
	if (state.current?.id === id) {
		state.current = null
	}
}

function clearErrors() {
	if (state.current?.type === 'error') {
		state.current = null
	}
}

export function useToast() {
	const current = readonly(toRef(state, 'current'))

	return {
		current,
		success: (message) => add('success', message),
		error: (message) => add('error', message),
		dismiss,
		clearErrors,
	}
}
