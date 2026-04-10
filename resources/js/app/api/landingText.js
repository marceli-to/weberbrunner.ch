import api from '@/api/axios'

export default {
	show: (page) => api.get(`/page-text/${page}`),
	update: (page, data) => api.put(`/page-text/${page}`, data),
}
