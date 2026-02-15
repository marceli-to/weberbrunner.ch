<script setup>
import { inject } from 'vue'
import Grid from '@/components/ui/grid/Grid.vue'

defineProps({
	header: { type: Boolean, default: false },
})

const cols = inject('listTableCols', 8)
</script>

<template>
	<Grid
		:cols="cols"
		:class="header ? '' : 'hover:bg-navy transition-colors group'">
		<slot />
	</Grid>
</template>
