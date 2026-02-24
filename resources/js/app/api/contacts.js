import api from '@/api/axios'

export default {
	index: () => api.get('/contacts'),
	show: (id) => api.get(`/contacts/${id}`),
	store: (data) => api.post('/contacts', data),
	update: (id, data) => api.put(`/contacts/${id}`, data),
	toggle: (id) => api.patch(`/contacts/${id}/toggle`),
	destroy: (id) => api.delete(`/contacts/${id}`),
	reorder: (items) => api.patch('/contacts/reorder', { items }),
	attachMedia: (id, media) => api.post(`/contacts/${id}/media`, { media }),
}
