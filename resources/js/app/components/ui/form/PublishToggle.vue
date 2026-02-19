<script setup>
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import Button from '@/components/ui/form/Button.vue'

const model = defineModel({ type: Boolean, default: false })
</script>

<template>
	<Grid :cols="8" class="bg-navy p-10">
		<Span class="col-span-4">
			<Button
				:variant="model ? 'toggle-active' : 'toggle'"
				class="justify-center"
				@click="model = true">
				Publiziert
			</Button>
		</Span>
		<Span class="col-span-4">
			<Button
				:variant="!model ? 'toggle-active' : 'toggle'"
				class="justify-center"
				@click="model = false">
				Nicht publiziert
			</Button>
		</Span>
	</Grid>
</template>
