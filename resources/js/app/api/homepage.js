import api from '@/api/axios'

export default {
	index: () => api.get('/homepage'),
	store: (data) => api.post('/homepage', data),
	destroy: (uuid) => api.delete(`/homepage/${uuid}`),
	reorder: (items) => api.patch('/homepage/reorder', { items }),
}
