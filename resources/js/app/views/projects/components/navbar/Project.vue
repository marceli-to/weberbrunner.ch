<script setup>
import { useRoute, useRouter } from 'vue-router'
import NavBar from '@/components/ui/navbar/NavBar.vue'
import NavBarButton from '@/components/ui/navbar/NavBarButton.vue'
import Window from '@/components/icons/Window.vue'
import Download from '@/components/icons/Download.vue'
import List from '@/components/icons/List.vue'
import Eye from '@/components/icons/Eye.vue'
import projectsApi from '@/api/projects'
import { useProject } from '@/composables/useProject'
import { useToast } from '@/composables/useToast'

const route = useRoute()
const router = useRouter()
const toast = useToast()
const { project } = useProject(null, { skipFetch: true })

const items = [
	{ label: 'Web', name: 'projects.layout', icon: Window },
	{ label: 'Rohdaten', name: null, icon: Download },
	{ label: 'Referenzblatt', name: null, icon: List },
]

function navigate(name) {
	if (name) {
		router.push({ name, params: { id: route.params.id } })
	}
}

async function togglePublish() {
	const previous = project.value.publish
	project.value.publish = !previous
	try {
		await projectsApi.toggle(route.params.id)
		toast.success(project.value.publish ? 'Publiziert' : 'Nicht publiziert')
	} catch {
		project.value.publish = previous
		toast.error('Fehler beim Speichern')
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
		<NavBarButton
			class="!border-none"
			:class="project?.publish ? '!bg-lime !text-white' : '!bg-silver !text-white'"
			@click="togglePublish">
			<template #icon>
				<Eye class="w-14 h-auto" />
			</template>
			{{ project?.publish ? 'Publiziert' : 'Nicht publiziert' }}
		</NavBarButton>
	</NavBar>
</template>
