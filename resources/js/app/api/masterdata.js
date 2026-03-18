import api from '@/api/axios'

export default {
	index: () => api.get('/masterdata'),
	store: (data) => api.post('/masterdata', data),
	update: (uuid, data) => api.put(`/masterdata/${uuid}`, data),
	toggleStandard: (uuid) => api.patch(`/masterdata/${uuid}/standard`),
	destroy: (uuid) => api.delete(`/masterdata/${uuid}`),
	reorder: (items) => api.patch('/masterdata/reorder', { items }),
}
