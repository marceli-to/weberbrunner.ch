<script setup>
defineOptions({ inheritAttrs: false })
defineProps({
	variant: { type: String, default: 'right', validator: (v) => ['left', 'right'].includes(v) },
	size: { type: String, default: 'lg', validator: (v) => ['sm', 'lg'].includes(v) }
})
</script>

<template>
	<svg v-if="variant === 'right' && size === 'sm'" v-bind="$attrs" viewBox="0 0 10 8" fill="none" xmlns="http://www.w3.org/2000/svg">
		<path d="M6 0L5.28998 0.700012L8.08002 3.5H0V4.5H8.08002L5.28998 7.29001L6 8L10 4L6 0Z" fill="currentColor"/>
	</svg>
	<svg v-else-if="variant === 'right' && size === 'lg'" v-bind="$attrs" viewBox="0 0 15 12" fill="none" xmlns="http://www.w3.org/2000/svg">
		<path d="M9 0L8.14996 0.850006L12.7 5.40002H0V6.60001H12.7L8.14996 11.15L9 12L15 6L9 0Z" fill="currentColor"/>
	</svg>
	<svg v-else-if="variant === 'left' && size === 'sm'" v-bind="$attrs" viewBox="0 0 10 8" fill="none" xmlns="http://www.w3.org/2000/svg">
		<path d="M4 8L4.71002 7.29999L1.91998 4.5H10V3.5H1.91998L4.71002 0.709988L4 0L0 4L4 8Z" fill="currentColor"/>
	</svg>
	<svg v-else v-bind="$attrs" viewBox="0 0 25 20" fill="none" xmlns="http://www.w3.org/2000/svg">
		<path d="M10 0L0 10L10 20L11.41 18.58L3.82001 11H25V9H3.82001L11.41 1.41L10 0Z" fill="currentColor"/>
	</svg>
</template>
