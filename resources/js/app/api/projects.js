import api from '@/api/axios'

export default {
	index: () => api.get('/projects'),
	published: () => api.get('/projects/published'),
	show: (id) => api.get(`/projects/${id}`),
	store: (data) => api.post('/projects', data),
	update: (id, data) => api.put(`/projects/${id}`, data),
	destroy: (id) => api.delete(`/projects/${id}`),
	reorder: (items) => api.patch('/projects/reorder', { items }),
	attachMedia: (id, media) => api.post(`/projects/${id}/media`, { media }),
	toggle: (id) => api.patch(`/projects/${id}/toggle`),
	updateMetaDescription: (id, data) => api.patch(`/projects/${id}/meta-description`, data),
	updateText: (id, data) => api.patch(`/projects/${id}/text`, data),
	syncCategories: (id, categories) => api.patch(`/projects/${id}/categories`, { categories }),
	syncStatuses: (id, statuses) => api.patch(`/projects/${id}/statuses`, { statuses }),
}
