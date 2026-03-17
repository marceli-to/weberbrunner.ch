<script setup>
defineOptions({ inheritAttrs: false })
defineProps({
	variant: { type: String, default: 'down', validator: (v) => ['up', 'down'].includes(v) },
	size: { type: String, default: 'sm', validator: (v) => ['sm', 'lg'].includes(v) }
})
</script>

<template>
	<svg v-if="variant === 'up' && size === 'sm'" v-bind="$attrs" viewBox="0 0 12 8" fill="none" xmlns="http://www.w3.org/2000/svg">
		<path d="M6 0L12 6L10.94 7.06L6 2.12L1.06 7.06L0 6L6 0Z" fill="currentColor"/>
	</svg>
	<svg v-else-if="variant === 'up' && size === 'lg'" v-bind="$attrs" viewBox="0 0 25 14" fill="none" xmlns="http://www.w3.org/2000/svg">
		<path d="M12.45 0L24.8899 12.44L23.33 14L12.45 3.11002L1.55994 14L0 12.44L12.45 0Z" fill="currentColor"/>
	</svg>
	<svg v-else-if="variant === 'down' && size === 'sm'" v-bind="$attrs" viewBox="0 0 12 8" fill="none" xmlns="http://www.w3.org/2000/svg">
		<path d="M5.98999 7.06L0 1.06L1.06 0L5.98999 4.94L10.93 0L12 1.06L5.98999 7.06Z" fill="currentColor"/>
	</svg>
	<svg v-else v-bind="$attrs" viewBox="0 0 25 14" fill="none" xmlns="http://www.w3.org/2000/svg">
		<path d="M12.45 14L0 1.55002L1.56 0L12.45 10.89L23.33 0L24.89 1.55002L12.45 14Z" fill="currentColor"/>
	</svg>
</template>
