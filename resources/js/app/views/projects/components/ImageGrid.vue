<script setup>
import { useRouter } from 'vue-router'
import Card from '@/components/ui/Card.vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import PencilCircle from '@/components/icons/PencilCircle.vue'
import MediaCard from '@/components/media/MediaCard.vue'
import { useCan } from '@/composables/useCan'

const props = defineProps({
	project: { type: Object, required: true },
})

const router = useRouter()
const { canUpdate } = useCan()

function editImages() {
	router.push({ name: 'projects.images.edit', params: { id: props.project.uuid } })
}
</script>

<template>
	<Card header>
		<Grid :cols="6">
			<Span class="col-span-8 font-semibold text-md min-h-50 flex items-center justify-between border-b-thin">
				<span>Bilder</span>
				<button v-if="canUpdate" type="button" class="cursor-pointer" @click="editImages">
					<PencilCircle class="w-25" />
				</button>
			</Span>
		</Grid>
		<div v-if="project.media?.length" class="grid grid-cols-2 lg:grid-cols-5 gap-20 pt-20">
			<MediaCard
				v-for="item in project.media"
				:key="item.uuid"
				:item="item"
        compact />
		</div>
	</Card>
</template>
