import api from '@/api/axios'

export default {
	show: () => api.get('/landing/text'),
	update: (data) => api.put('/landing/text', data),
}
