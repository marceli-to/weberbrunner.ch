import api from '@/api/axios'

export default {
	index: () => api.get('/statuses'),
	store: (title) => api.post('/statuses', { title }),
}
