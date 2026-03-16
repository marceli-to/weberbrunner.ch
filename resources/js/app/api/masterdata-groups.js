import api from '@/api/axios'

export default {
	index: () => api.get('/masterdata-groups'),
	store: (title) => api.post('/masterdata-groups', { title }),
	update: (uuid, title) => api.put(`/masterdata-groups/${uuid}`, { title }),
	destroy: (uuid) => api.delete(`/masterdata-groups/${uuid}`),
	reorder: (items) => api.patch('/masterdata-groups/reorder', { items }),
}
