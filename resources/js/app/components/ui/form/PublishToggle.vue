<script setup>
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import Button from '@/components/ui/form/Button.vue'
import { useToast } from '@/composables/useToast'

const model = defineModel({ type: Boolean, default: false })
const toast = useToast()

function toggle(value) {
	model.value = value
	toast.success(value ? 'Publiziert' : 'Nicht publiziert')
}
</script>

<template>
	<Grid :cols="8" class="bg-navy p-10">
		<Span class="col-span-4">
			<Button
				:variant="model ? 'toggle-active' : 'toggle'"
				class="justify-center"
				@click="toggle(true)">
				Publiziert
			</Button>
		</Span>
		<Span class="col-span-4">
			<Button
				:variant="!model ? 'toggle-active' : 'toggle'"
				class="justify-center"
				@click="toggle(false)">
				Nicht publiziert
			</Button>
		</Span>
	</Grid>
</template>
