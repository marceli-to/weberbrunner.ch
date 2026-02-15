import api from './axios'

export default {
	index: () => api.get('/team'),
	show: (id) => api.get(`/team/${id}`),
	store: (data) => api.post('/team', data),
	update: (id, data) => api.put(`/team/${id}`, data),
	destroy: (id) => api.delete(`/team/${id}`),
	reorder: (items) => api.patch('/team/reorder', { items }),
	bios: {
		index: (teamId) => api.get(`/team/${teamId}/cv`),
		store: (teamId, data) => api.post(`/team/${teamId}/cv`, data),
		update: (teamId, bioId, data) => api.put(`/team/${teamId}/cv/${bioId}`, data),
		destroy: (teamId, bioId) => api.delete(`/team/${teamId}/cv/${bioId}`),
		reorder: (teamId, items) => api.patch(`/team/${teamId}/cv/reorder`, { items }),
	},
}
