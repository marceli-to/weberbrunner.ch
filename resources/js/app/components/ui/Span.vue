<script setup>
import { computed } from 'vue'

const props = defineProps({
	cols: { type: Number, required: true },
	start: { type: Number, default: null },
})

const classes = computed(() => {
	const c = [`col-span-${props.cols}`]
	if (props.start) c.push(`col-start-${props.start}`)
	return c
})
</script>

<template>
	<div :class="classes">
		<slot />
	</div>
</template>
