import api from '@/api/axios'

export default {
	index: () => api.get('/publications'),
	show: (id) => api.get(`/publications/${id}`),
	store: (data) => api.post('/publications', data),
	update: (id, data) => api.put(`/publications/${id}`, data),
	toggle: (id) => api.patch(`/publications/${id}/toggle`),
	destroy: (id) => api.delete(`/publications/${id}`),
	reorder: (items) => api.patch('/publications/reorder', { items }),
	attachMedia: (id, media) => api.post(`/publications/${id}/media`, { media }),
	attributes: {
		index: (pubId) => api.get(`/publications/${pubId}/attributes`),
		store: (pubId, data) => api.post(`/publications/${pubId}/attributes`, data),
		update: (pubId, attrId, data) => api.put(`/publications/${pubId}/attributes/${attrId}`, data),
		destroy: (pubId, attrId) => api.delete(`/publications/${pubId}/attributes/${attrId}`),
		reorder: (pubId, items) => api.patch(`/publications/${pubId}/attributes/reorder`, { items }),
	},
}
