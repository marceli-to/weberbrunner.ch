import api from '@/api/axios'

export default {
	index: () => api.get('/statuses'),
	store: (title) => api.post('/statuses', { title }),
	update: (uuid, title) => api.put(`/statuses/${uuid}`, { title }),
	destroy: (uuid) => api.delete(`/statuses/${uuid}`),
	reorder: (items) => api.patch('/statuses/reorder', { items }),
}
