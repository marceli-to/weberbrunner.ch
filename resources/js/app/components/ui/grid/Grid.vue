<script setup>
import { computed } from 'vue'

const props = defineProps({
	cols: { type: Number, default: 10 },
	as: { type: [String, Object], default: 'div' },
})

const colsClass = computed(() => `grid-cols-${props.cols}`)
</script>

<template>
	<component :is="as" class="grid gap-20" :class="colsClass">
		<slot />
	</component>
</template>
