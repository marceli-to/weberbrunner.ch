import api from './axios'

export default {
	index: () => api.get('/locations'),
	show: (id) => api.get(`/locations/${id}`),
}
