<script setup>
import Grid from '@/components/ui/grid/Grid.vue'
</script>

<template>
	<Grid :cols="12" class="fixed inset-0 -z-1 pointer-events-none">
		<div v-for="n in 12" :key="n" class="outline outline-red-100">&nbsp;</div>
	</Grid>
</template>
