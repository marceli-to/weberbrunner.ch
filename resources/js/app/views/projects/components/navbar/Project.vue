<script setup>
import { useRoute, useRouter } from 'vue-router'
import NavBar from '@/components/ui/navbar/NavBar.vue'
import NavBarButton from '@/components/ui/navbar/NavBarButton.vue'
import Window from '@/components/icons/Window.vue'
import Download from '@/components/icons/Download.vue'
import List from '@/components/icons/List.vue'
import Eye from '@/components/icons/Eye.vue'

const route = useRoute()
const router = useRouter()

const items = [
	{ label: 'Web', name: 'projects.layout', icon: Window },
	{ label: 'Rohdaten', name: null, icon: Download },
	{ label: 'Referenzblatt', name: null, icon: List },
	{ label: 'Publiziert', name: null, icon: Eye },
]

function navigate(name) {
	if (name) {
		router.push({ name, params: { id: route.params.id } })
	}
}
</script>

<template>
	<NavBar>
		<NavBarButton
			v-for="item in items"
			:key="item.label"
			:active="route.name === item.name"
			@click="navigate(item.name)">
			<template #icon>
				<component :is="item.icon" class="w-14 h-auto" />
			</template>
			{{ item.label }}
		</NavBarButton>
	</NavBar>
</template>
