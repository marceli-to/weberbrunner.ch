import api from './axios'

export default {
	index: () => api.get('/jury'),
	show: (id) => api.get(`/jury/${id}`),
	store: (data) => api.post('/jury', data),
	update: (id, data) => api.put(`/jury/${id}`, data),
	toggle: (id) => api.patch(`/jury/${id}/toggle`),
	destroy: (id) => api.delete(`/jury/${id}`),
	reorder: (items) => api.patch('/jury/reorder', { items }),
}
