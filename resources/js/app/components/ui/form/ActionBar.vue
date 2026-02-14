<script setup>
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import Button from '@/components/ui/form/Button.vue'

defineEmits(['cancel'])
</script>

<template>
	<div class="sticky bottom-0 z-50 mt-auto">
		<Grid>
			<Span class="col-span-8 col-start-2 -mx-20 bg-navy flex gap-20 py-16 px-20">
				<Button type="submit" variant="ghost" class="flex-1 justify-center">Speichern</Button>
				<Button variant="ghost" @click="$emit('cancel')" class="flex-1 justify-center" type="button">Abbrechen</Button>
			</Span>
		</Grid>
	</div>
</template>
