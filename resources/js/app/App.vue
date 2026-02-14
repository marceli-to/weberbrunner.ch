<script setup>
import { RouterView } from 'vue-router'
import AppLayout from '@/components/layout/AppLayout.vue'
import { useAuthStore } from '@/stores/auth'

const authStore = useAuthStore()
authStore.fetchUser()
</script>

<template>
	<AppLayout>
		<RouterView />
	</AppLayout>
</template>
