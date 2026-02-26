import api from '@/api/axios'

export default {
	index: () => api.get('/categories'),
	store: (title) => api.post('/categories', { title }),
}
