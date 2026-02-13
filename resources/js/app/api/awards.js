import api from './axios'

export default {
	index: () => api.get('/awards'),
	show: (id) => api.get(`/awards/${id}`),
	store: (data) => api.post('/awards', data),
	update: (id, data) => api.put(`/awards/${id}`, data),
	destroy: (id) => api.delete(`/awards/${id}`),
	reorder: (items) => api.patch('/awards/reorder', { items }),
}
