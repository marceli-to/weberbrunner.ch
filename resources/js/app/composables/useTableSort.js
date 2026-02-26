import { ref, computed, watch } from 'vue'

export function useTableSort(items, defaultKey = null, defaultDir = 'asc', storageKey = null) {
	const initial = loadFromStorage(storageKey)
	const sortKey = ref(initial?.key ?? defaultKey)
	const sortDir = ref(initial?.dir ?? defaultDir)

	if (storageKey) {
		watch([sortKey, sortDir], ([key, dir]) => {
			localStorage.setItem(`tableSort:${storageKey}`, JSON.stringify({ key, dir }))
		})
	}

	const sorted = computed(() => {
		if (!sortKey.value) return items.value

		return [...items.value].sort((a, b) => {
			const valA = resolve(a, sortKey.value)
			const valB = resolve(b, sortKey.value)

			if (valA == null && valB == null) return 0
			if (valA == null) return 1
			if (valB == null) return -1

			const numA = Number(valA)
			const numB = Number(valB)
			const cmp = !isNaN(numA) && !isNaN(numB)
				? numA - numB
				: String(valA).localeCompare(String(valB), 'de', { sensitivity: 'base' })
			return sortDir.value === 'asc' ? cmp : -cmp
		})
	})

	function toggleSort(key) {
		if (sortKey.value === key) {
			sortDir.value = sortDir.value === 'asc' ? 'desc' : 'asc'
		} else {
			sortKey.value = key
			sortDir.value = 'asc'
		}
	}

	function resolve(obj, path) {
		return path.split('.').reduce((o, k) => o?.[k], obj)
	}

	return { sorted, sortKey, sortDir, toggleSort }
}

function loadFromStorage(storageKey) {
	if (!storageKey) return null
	try {
		return JSON.parse(localStorage.getItem(`tableSort:${storageKey}`))
	} catch {
		return null
	}
}
