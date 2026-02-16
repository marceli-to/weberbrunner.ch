<script setup>
import Card from '@/components/ui/Card.vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import PencilCircle from '@/components/icons/PencilCircle.vue'
import MediaCard from '@/components/media/MediaCard.vue'

defineProps({
	project: { type: Object, required: true },
})
</script>

<template>
	<Card has-header>
		<Grid :cols="6">
			<Span class="col-span-2 font-semibold text-md min-h-50 flex items-center border-b-thin">
				Bilder
			</Span>
			<Span class="col-span-4 min-h-50 flex items-center justify-end border-b-thin">
				<button type="button" class="cursor-pointer">
					<PencilCircle class="w-25" />
				</button>
			</Span>
		</Grid>
		<div v-if="project.media?.length" class="grid grid-cols-2 lg:grid-cols-5 gap-20 pt-20">
			<MediaCard
				v-for="item in project.media"
				:key="item.uuid"
				:item="item" />
		</div>
	</Card>
</template>
