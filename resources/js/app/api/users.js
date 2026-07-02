import api from '@/api/axios'

export default {
	index: () => api.get('/users'),
	store: (data) => api.post('/users', data),
	update: (uuid, data) => api.put(`/users/${uuid}`, data),
	destroy: (uuid) => api.delete(`/users/${uuid}`),
}
