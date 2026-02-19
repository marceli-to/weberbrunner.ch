<script setup>
import Card from '@/components/ui/Card.vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import PencilCircle from '@/components/icons/PencilCircle.vue'

defineProps({
	project: { type: Object, required: true },
})
</script>

<template>
	<Card header>
		<Grid :cols="6">
			<Span class="col-span-2 font-semibold text-md min-h-50 flex items-center border-b-thin">
				Stammdaten
			</Span>
			<Span class="col-span-4 min-h-50 flex items-center justify-end border-b-thin">
				<button type="button" class="cursor-pointer">
					<PencilCircle class="w-25" />
				</button>
			</Span>
		</Grid>
	</Card>
</template>
