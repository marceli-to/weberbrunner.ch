<script setup>
import { computed } from 'vue'

const props = defineProps({
	cols: { type: Number, default: 10 },
})

const colsClass = computed(() => `grid-cols-${props.cols}`)
</script>

<template>
	<div class="grid gap-20" :class="colsClass">
		<slot />
	</div>
</template>
