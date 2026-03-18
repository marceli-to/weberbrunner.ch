import api from '@/api/axios'

export default {
	index: (projectUuid) => api.get(`/projects/${projectUuid}/masterdata`),
	attached: (projectUuid) => api.get(`/projects/${projectUuid}/masterdata/attached`),
	sync: (projectUuid, entries) => api.patch(`/projects/${projectUuid}/masterdata`, { entries }),
	reorder: (projectUuid, items) => api.patch(`/projects/${projectUuid}/masterdata/reorder`, { items }),
	destroy: (projectUuid, masterdataUuid) => api.delete(`/projects/${projectUuid}/masterdata/${masterdataUuid}`),
}
