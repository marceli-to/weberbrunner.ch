<script setup>
import { useRoute, useRouter } from 'vue-router'
import NavBar from '@/components/ui/navbar/NavBar.vue'
import NavBarButton from '@/components/ui/navbar/NavBarButton.vue'

const route = useRoute()
const router = useRouter()

const items = [
	{ label: 'Layout', name: 'projects.layout' },
	{ label: 'Metadaten', name: 'projects.metadata' },
	{ label: 'Teaserbild', name: 'projects.teaser_image' },
	{ label: 'Einstellungen', name: 'projects.settings' },
]

function navigate(name) {
	router.push({ name, params: { id: route.params.id } })
}
</script>

<template>
	<NavBar>
		<NavBarButton
			v-for="item in items"
			:key="item.name"
			:active="route.name === item.name"
			@click="navigate(item.name)">
			{{ item.label }}
		</NavBarButton>
	</NavBar>
</template>
