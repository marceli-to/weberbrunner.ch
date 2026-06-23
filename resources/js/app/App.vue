<script setup>
import { ref } from 'vue'
import { RouterView } from 'vue-router'
import AppLayout from '@/components/layout/AppLayout.vue'
import { useAuthStore } from '@/stores/auth'

const authStore = useAuthStore()
const appReady = ref(false)

authStore.ensureUser().then(() => {
	appReady.value = true
})
</script>

<template>
	<AppLayout v-if="appReady">
		<RouterView />
	</AppLayout>
</template>
