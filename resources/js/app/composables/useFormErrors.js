import { ref } from 'vue'

export function useFormErrors() {
	const errors = ref({})

	function get(field) {
		const fieldErrors = errors.value[field]
		return fieldErrors?.length ? fieldErrors[0] : null
	}

	function clear(field) {
		if (field) {
			delete errors.value[field]
		} else {
			errors.value = {}
		}
	}

	async function submit(apiCall) {
		clear()
		try {
			await apiCall()
			return true
		} catch (error) {
			if (error.response?.status === 422) {
				errors.value = error.response.data.errors || {}
			}
			return false
		}
	}

	return { errors, get, clear, submit }
}
