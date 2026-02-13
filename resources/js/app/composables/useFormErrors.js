import { ref } from 'vue'
import { useToast } from '@/composables/useToast'

export function useFormErrors({ toast: showToast = false } = {}) {
	const errors = ref({})
	const toast = showToast ? useToast() : null

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
				if (toast) {
					toast.error('Bitte überprüfen Sie Ihre Eingaben.')
				}
			}
			return false
		}
	}

	return { errors, get, clear, submit }
}
