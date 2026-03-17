<script setup>
import Grid from '@/components/ui/grid/Grid.vue'
</script>

<template>
	<Grid as="nav" :cols="8">
		<slot />
	</Grid>
</template>
