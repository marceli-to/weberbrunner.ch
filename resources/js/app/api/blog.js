import api from './axios'

export default {
	index: () => api.get('/blog'),
	show: (id) => api.get(`/blog/${id}`),
	store: (data) => api.post('/blog', data),
	update: (id, data) => api.put(`/blog/${id}`, data),
	destroy: (id) => api.delete(`/blog/${id}`),
	reorder: (items) => api.patch('/blog/reorder', { items }),
}
