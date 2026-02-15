import api from './axios'

export default {
	index: () => api.get('/team'),
	show: (id) => api.get(`/team/${id}`),
	store: (data) => api.post('/team', data),
	update: (id, data) => api.put(`/team/${id}`, data),
	destroy: (id) => api.delete(`/team/${id}`),
	reorder: (items) => api.patch('/team/reorder', { items }),
}
