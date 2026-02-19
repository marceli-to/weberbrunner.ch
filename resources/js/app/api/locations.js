import api from '@/api/axios'

export default {
	index: () => api.get('/locations'),
	show: (id) => api.get(`/locations/${id}`),
}
