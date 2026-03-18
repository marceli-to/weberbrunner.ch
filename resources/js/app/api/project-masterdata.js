import api from '@/api/axios'

export default {
	all: (projectUuid) => api.get(`/projects/${projectUuid}/masterdata`),
	attached: (projectUuid) => api.get(`/projects/${projectUuid}/masterdata/attached`),
	available: (projectUuid) => api.get(`/projects/${projectUuid}/masterdata/available`),
	updateValues: (projectUuid, entries) => api.patch(`/projects/${projectUuid}/masterdata`, { entries }),
	reorder: (projectUuid, items) => api.patch(`/projects/${projectUuid}/masterdata/reorder`, { items }),
	attach: (projectUuid, masterdataUuid) => api.post(`/projects/${projectUuid}/masterdata/${masterdataUuid}`),
	destroy: (projectUuid, masterdataUuid) => api.delete(`/projects/${projectUuid}/masterdata/${masterdataUuid}`),
}
