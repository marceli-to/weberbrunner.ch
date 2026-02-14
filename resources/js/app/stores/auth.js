import { defineStore } from 'pinia'
import authApi from '@/api/auth'

export const useAuthStore = defineStore('auth', {
	state: () => ({
		user: null,
	}),

	actions: {
		async fetchUser() {
			const { data } = await authApi.me()
			this.user = data.data
		},
	},
})
