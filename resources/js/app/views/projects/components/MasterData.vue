<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import projectMasterdataApi from '@/api/project-masterdata'
import Card from '@/components/ui/Card.vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import PencilCircle from '@/components/icons/PencilCircle.vue'

const props = defineProps({
	project: { type: Object, required: true },
})

const route = useRoute()
const router = useRouter()

const entries = ref([])

onMounted(async () => {
	const { data } = await projectMasterdataApi.all(props.project.uuid)
	entries.value = data.data
})

function edit() {
	router.push({ name: 'projects.masterdata.edit', params: { id: route.params.id } })
}
</script>

<template>
	<Card header>
		<Grid :cols="6">
			<Span class="col-span-2 font-semibold text-md min-h-50 flex items-center border-b-thin">
				Stammdaten
			</Span>
			<Span class="col-span-4 min-h-50 flex items-center justify-end border-b-thin">
				<button type="button" class="cursor-pointer" @click="edit">
					<PencilCircle class="w-25" />
				</button>
			</Span>
		</Grid>
		<div v-for="entry in entries" :key="entry.uuid">
			<Grid :cols="6" class="min-h-30 text-md">
				<Span class="col-span-2 font-semibold border-b-thin border-b-gray flex items-center">{{ entry.title }}</Span>
				<Span class="col-span-4 border-b-thin border-b-gray flex items-center">{{ entry.value }}</Span>
			</Grid>
		</div>
	</Card>
</template>
