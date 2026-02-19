import api from '@/api/axios'

export default {
	index: () => api.get('/talks'),
	show: (id) => api.get(`/talks/${id}`),
	store: (data) => api.post('/talks', data),
	update: (id, data) => api.put(`/talks/${id}`, data),
	toggle: (id) => api.patch(`/talks/${id}/toggle`),
	destroy: (id) => api.delete(`/talks/${id}`),
	reorder: (items) => api.patch('/talks/reorder', { items }),
}
