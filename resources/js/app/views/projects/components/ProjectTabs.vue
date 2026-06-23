<script setup>
import Window from '@/components/icons/Window.vue'
import Download from '@/components/icons/Download.vue'
import List from '@/components/icons/List.vue'
import projectsApi from '@/api/projects'
import { useRoute } from 'vue-router'
import { useProject } from '@/composables/useProject'
import { useToast } from '@/composables/useToast'
import { useCan } from '@/composables/useCan'
import Tabs from '@/components/ui/navbar/Tabs.vue'

const route = useRoute()
const toast = useToast()
const { project } = useProject(null, { skipFetch: true })
const { canUpdate } = useCan()

const items = [
	{ label: 'Web', name: 'projects.layout', icon: Window },
	{ label: 'Rohdaten', name: null, icon: Download },
	{ label: 'Referenzblatt', name: null, icon: List },
]

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
	<Tabs :items="items" :publishable="project" :can-publish="canUpdate" @toggle-publish="togglePublish" />
</template>
