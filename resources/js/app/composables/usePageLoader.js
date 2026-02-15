import { reactive, readonly } from 'vue'

const state = reactive({
	loading: false,
})

let activeKey = 0

function load(fn) {
	const key = ++activeKey
	state.loading = true
	fn().catch(() => {}).finally(() => {
		if (key === activeKey) {
			state.loading = false
		}
	})
}

function reset() {
	activeKey++
	state.loading = false
}

export function usePageLoader() {
	return {
		state: readonly(state),
		load,
		reset,
	}
}
