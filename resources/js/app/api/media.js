import api from '@/api/axios'

export default {
	index: (search) => api.get('/media', { params: { search } }),
	persist: (data) => api.post('/media/persist', data),
	upload: (data) => api.post('/media/upload', data, {
		headers: { 'Content-Type': 'multipart/form-data' },
	}),
	update: (uuid, data) => api.put(`/media/${uuid}`, data),
	destroy: (uuid) => api.delete(`/media/${uuid}`),
	reorder: (items) => api.patch('/media/reorder', { items }),
	teaser: (uuid) => api.patch(`/media/${uuid}/teaser`),
	og: (uuid) => api.patch(`/media/${uuid}/og`),
	togglePublish: (uuid) => api.patch(`/media/${uuid}/publish`),
}
