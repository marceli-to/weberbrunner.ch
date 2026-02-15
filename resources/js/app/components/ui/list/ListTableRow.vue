<script setup>
import { inject } from 'vue'
import Grid from '@/components/ui/grid/Grid.vue'

defineProps({
	header: { type: Boolean, default: false },
	to: { type: Object, default: null },
})

const cols = inject('listTableCols', 8)
</script>

<template>
	<RouterLink v-if="to && !header" :to="to" class="block">
		<Grid
			:cols="cols"
			class="hover:bg-navy group">
			<slot />
		</Grid>
	</RouterLink>
	<Grid
		v-else
		:cols="cols"
		:class="header ? '' : 'hover:bg-navy group'">
		<slot />
	</Grid>
</template>
