<script setup>
import { useRoute, useRouter } from 'vue-router'
import NavBar from '@/components/ui/navbar/NavBar.vue'
import NavBarButton from '@/components/ui/navbar/NavBarButton.vue'
import Eye from '@/components/icons/Eye.vue'

const props = defineProps({
	items: { type: Array, required: true },
	publishable: { type: Object, default: null },
})

const emit = defineEmits(['toggle-publish'])

const route = useRoute()
const router = useRouter()

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
			<template v-if="item.icon" #icon>
				<component :is="item.icon" class="w-14 h-auto" />
			</template>
			{{ item.label }}
		</NavBarButton>
		<NavBarButton
			v-if="publishable"
			class="!border-none"
			:class="publishable.publish ? '!bg-lime !text-white' : '!bg-silver !text-white'"
			@click="emit('toggle-publish')">
			<template #icon>
				<Eye class="w-14 h-auto" />
			</template>
			{{ publishable.publish ? 'Publiziert' : 'Nicht publiziert' }}
		</NavBarButton>
	</NavBar>
</template>
