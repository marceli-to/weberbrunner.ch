<script setup>
import { provide } from 'vue'

const props = defineProps({
	cols: { type: Number, default: 8 },
})

provide('listTableCols', props.cols)
</script>

<template>
	<div class="pb-20 bg-white">
		<slot />
	</div>
</template>
