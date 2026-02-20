import api from '@/api/axios'

export default {
	index: (projectId) => api.get(`/projects/${projectId}/blocks`),
	store: (projectId, data) => api.post(`/projects/${projectId}/blocks`, data),
	update: (projectId, blockUuid, data) => api.put(`/projects/${projectId}/blocks/${blockUuid}`, data),
	destroy: (projectId, blockUuid) => api.delete(`/projects/${projectId}/blocks/${blockUuid}`),
	reorder: (projectId, items) => api.patch(`/projects/${projectId}/blocks/reorder`, { items }),
	selectMedia: (projectId, blockUuid, mediaUuids) => api.post(`/projects/${projectId}/blocks/${blockUuid}/media/select`, { media_uuids: mediaUuids }),
	detachMedia: (projectId, blockUuid, mediaUuid) => api.delete(`/projects/${projectId}/blocks/${blockUuid}/media/${mediaUuid}`),
	storeLink: (projectId, blockUuid, data) => api.post(`/projects/${projectId}/blocks/${blockUuid}/links`, data),
	updateLink: (projectId, blockUuid, linkUuid, data) => api.put(`/projects/${projectId}/blocks/${blockUuid}/links/${linkUuid}`, data),
	destroyLink: (projectId, blockUuid, linkUuid) => api.delete(`/projects/${projectId}/blocks/${blockUuid}/links/${linkUuid}`),
	reorderLinks: (projectId, blockUuid, items) => api.patch(`/projects/${projectId}/blocks/${blockUuid}/links/reorder`, { items }),
}
