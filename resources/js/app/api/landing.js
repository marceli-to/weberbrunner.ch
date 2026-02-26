import api from '@/api/axios'

export default {
	index: () => api.get('/landing'),
	store: (data) => api.post('/landing', data),
	destroy: (uuid) => api.delete(`/landing/${uuid}`),
	reorder: (items) => api.patch('/landing/reorder', { items }),
}
