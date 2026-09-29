import axios from 'axios'
import { useToast } from '@/composables/useToast'
import { startSaving, finishSaving } from '@/composables/useSaving'

const MUTATING_METHODS = ['post', 'put', 'patch', 'delete']

const api = axios.create({
	baseURL: '/api/dashboard',
	headers: {
		'Content-Type': 'application/json',
		'Accept': 'application/json',
		'X-Requested-With': 'XMLHttpRequest',
	},
	withCredentials: true,
})

// Add CSRF token to all requests
api.interceptors.request.use((config) => {
	const token = document.querySelector('meta[name="csrf-token"]')?.content
	if (token) {
		config.headers['X-CSRF-TOKEN'] = token
	}
	if (MUTATING_METHODS.includes(config.method?.toLowerCase())) {
		config.tracksSaving = true
		startSaving()
	}
	return config
})

// Handle errors globally
api.interceptors.response.use(
	(response) => {
		if (response.config?.tracksSaving) finishSaving()
		return response
	},
	(error) => {
		if (error.config?.tracksSaving) finishSaving()
		if (error.response?.status === 401) {
			window.location.href = '/login'
		} else if (error.response?.status === 422) {
			// Pass through — handled by useFormErrors
		} else if (error.response?.status >= 500) {
			const toast = useToast()
			toast.error('Server error. Please try again.')
		} else if (!error.response) {
			const toast = useToast()
			toast.error('Network error. Please check your connection.')
		}
		return Promise.reject(error)
	}
)

export default api
