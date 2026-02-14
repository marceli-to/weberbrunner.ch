import api from './axios'

export default {
	me: () => api.get('/me'),
}
