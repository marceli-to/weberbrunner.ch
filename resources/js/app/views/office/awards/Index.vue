<script setup>
import PageTitle from '@/components/ui/PageTitle.vue'
import Grid from '@/components/ui/Grid.vue'
import Span from '@/components/ui/Span.vue'
</script>

<template>

	<Grid class="mb-20">

		<Span :cols="8" :start="2">
			<PageTitle>Auszeichnungen</PageTitle>
		</Span>
    
	</Grid>

	<Grid>

		<Span :cols="1" class="min-h-100 bg-blue-500 flex items-center justify-end">
			[drag]
		</Span>

		<Span :cols="8" class="min-h-100 bg-green-500">
		</Span>

		<Span :cols="1" class="min-h-100 bg-yellow-500 flex items-center justify-start">
			[delete]
		</Span>

	</Grid>

</template>
