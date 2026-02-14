import { reactive, watch } from 'vue'

export function useCollapsed(key) {
	const storageKey = `collapsed:${key}`
	const stored = JSON.parse(localStorage.getItem(storageKey) || '[]')
	const collapsed = reactive(new Set(stored))

	watch(() => [...collapsed], (val) => {
		localStorage.setItem(storageKey, JSON.stringify(val))
	})

	function toggle(uuid) {
		collapsed.has(uuid) ? collapsed.delete(uuid) : collapsed.add(uuid)
	}

	return { collapsed, toggle }
}
