import api from '@/api/axios'

export default {
	index: () => api.get('/publications'),
	show: (id) => api.get(`/publications/${id}`),
	store: (data) => api.post('/publications', data),
	update: (id, data) => api.put(`/publications/${id}`, data),
	toggle: (id) => api.patch(`/publications/${id}/toggle`),
	destroy: (id) => api.delete(`/publications/${id}`),
	reorder: (items) => api.patch('/publications/reorder', { items }),
	attachMedia: (id, media) => api.post(`/publications/${id}/media`, { media }),
	attributes: {
		index: (pubId) => api.get(`/publications/${pubId}/attributes`),
		store: (pubId, data) => api.post(`/publications/${pubId}/attributes`, data),
		update: (pubId, attrId, data) => api.put(`/publications/${pubId}/attributes/${attrId}`, data),
		destroy: (pubId, attrId) => api.delete(`/publications/${pubId}/attributes/${attrId}`),
		reorder: (pubId, items) => api.patch(`/publications/${pubId}/attributes/reorder`, { items }),
	},
	blocks: {
		index: (pubId) => api.get(`/publications/${pubId}/blocks`),
		store: (pubId, data) => api.post(`/publications/${pubId}/blocks`, data),
		update: (pubId, blockId, data) => api.put(`/publications/${pubId}/blocks/${blockId}`, data),
		destroy: (pubId, blockId) => api.delete(`/publications/${pubId}/blocks/${blockId}`),
		reorder: (pubId, items) => api.patch(`/publications/${pubId}/blocks/reorder`, { items }),
		selectMedia: (pubId, blockId, mediaUuids) => api.post(`/publications/${pubId}/blocks/${blockId}/media/select`, { media_uuids: mediaUuids }),
		detachMedia: (pubId, blockId, mediaId) => api.delete(`/publications/${pubId}/blocks/${blockId}/media/${mediaId}`),
		uploadFile: (pubId, blockId, formData) => api.post(`/publications/${pubId}/blocks/${blockId}/media/upload`, formData, {
			headers: { 'Content-Type': 'multipart/form-data' },
		}),
	},
}
