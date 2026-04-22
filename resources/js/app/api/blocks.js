import api from '@/api/axios'

export function createBlocksApi(parentPath) {
	return {
		index: (parentId) => api.get(`/${parentPath}/${parentId}/blocks`),
		store: (parentId, data) => api.post(`/${parentPath}/${parentId}/blocks`, data),
		update: (parentId, blockUuid, data) => api.put(`/${parentPath}/${parentId}/blocks/${blockUuid}`, data),
		destroy: (parentId, blockUuid) => api.delete(`/${parentPath}/${parentId}/blocks/${blockUuid}`),
		reorder: (parentId, items) => api.patch(`/${parentPath}/${parentId}/blocks/reorder`, { items }),
		selectMedia: (parentId, blockUuid, mediaUuids) => api.post(`/${parentPath}/${parentId}/blocks/${blockUuid}/media/select`, { media_uuids: mediaUuids }),
		detachMedia: (parentId, blockUuid, mediaUuid) => api.delete(`/${parentPath}/${parentId}/blocks/${blockUuid}/media/${mediaUuid}`),
		uploadFile: (parentId, blockUuid, formData) => api.post(`/${parentPath}/${parentId}/blocks/${blockUuid}/media/upload`, formData, {
			headers: { 'Content-Type': 'multipart/form-data' },
		}),
		storeLink: (parentId, blockUuid, data) => api.post(`/${parentPath}/${parentId}/blocks/${blockUuid}/links`, data),
		updateLink: (parentId, blockUuid, linkUuid, data) => api.put(`/${parentPath}/${parentId}/blocks/${blockUuid}/links/${linkUuid}`, data),
		toggleLink: (parentId, blockUuid, linkUuid) => api.patch(`/${parentPath}/${parentId}/blocks/${blockUuid}/links/${linkUuid}/toggle`),
		destroyLink: (parentId, blockUuid, linkUuid) => api.delete(`/${parentPath}/${parentId}/blocks/${blockUuid}/links/${linkUuid}`),
		reorderLinks: (parentId, blockUuid, items) => api.patch(`/${parentPath}/${parentId}/blocks/${blockUuid}/links/reorder`, { items }),
	}
}

export const projectBlocksApi = createBlocksApi('projects')
export const publicationBlocksApi = createBlocksApi('publications')
export const pageBlocksApi = createBlocksApi('pages')
