import api from '@/api/axios'

export default {
	index: () => api.get('/categories'),
	store: (title) => api.post('/categories', { title }),
	update: (uuid, title) => api.put(`/categories/${uuid}`, { title }),
	destroy: (uuid) => api.delete(`/categories/${uuid}`),
	reorder: (items) => api.patch('/categories/reorder', { items }),
}
