import { defineStore } from 'pinia'
import blogApi from '@/api/blog'

export const useBlogStore = defineStore('blog', {
	state: () => ({
		posts: [],
		current: null,
		loading: false,
		errors: {},
	}),

	actions: {
		async fetchPosts() {
			this.loading = true
			try {
				const { data } = await blogApi.index()
				this.posts = data.data
			} finally {
				this.loading = false
			}
		},

		async fetchPost(id) {
			this.loading = true
			try {
				const { data } = await blogApi.show(id)
				this.current = data.data
			} finally {
				this.loading = false
			}
		},

		async savePost(form, id = null, media = []) {
			this.errors = {}
			try {
				const payload = { ...form }
				if (media.length) {
					payload.media = media
				}
				if (id) {
					await blogApi.update(id, payload)
				} else {
					await blogApi.store(payload)
				}
				return true
			} catch (error) {
				if (error.response?.status === 422) {
					this.errors = error.response.data.errors
				}
				return false
			}
		},

		async deletePost(id) {
			await blogApi.destroy(id)
			this.posts = this.posts.filter(p => p.id !== id)
		},

		async reorderPosts() {
			const items = this.posts.map((post, index) => ({
				id: post.id,
				sort_order: index,
			}))
			await blogApi.reorder(items)
		},
	},
})
