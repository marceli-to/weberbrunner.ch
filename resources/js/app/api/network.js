import api from '@/api/axios'

export default {
	index: () => api.get('/network'),
	show: (id) => api.get(`/network/${id}`),
	store: (data) => api.post('/network', data),
	update: (id, data) => api.put(`/network/${id}`, data),
	toggle: (id) => api.patch(`/network/${id}/toggle`),
	destroy: (id) => api.delete(`/network/${id}`),
	reorder: (items) => api.patch('/network/reorder', { items }),
}
