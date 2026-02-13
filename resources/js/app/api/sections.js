import api from './axios'

export default {
	index: (params) => api.get('/sections', { params }),
	show: (id) => api.get(`/sections/${id}`),
	store: (data) => api.post('/sections', data),
	update: (id, data) => api.put(`/sections/${id}`, data),
	destroy: (id) => api.delete(`/sections/${id}`),
	reorder: (items) => api.patch('/sections/reorder', { items }),
}
