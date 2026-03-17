<script setup>
defineProps({
	variant: {
		type: String,
		default: null,
	},
})
</script>

<template>
	<svg viewBox="0 0 18 10" fill="none" xmlns="http://www.w3.org/2000/svg">
		<template v-if="variant === 'sm'">
			<path d="M18 0H0V1H18V0Z" fill="currentColor"/>
			<path d="M18 9H0V10H18V9Z" fill="currentColor"/>
			<path d="M18 4.5H0V5.5H18V4.5Z" fill="currentColor"/>
		</template>
		<template v-else>
			<path d="M18 0H0V1.5H18V0Z" fill="currentColor"/>
			<path d="M18 8.5H0V10H18V8.5Z" fill="currentColor"/>
			<path d="M18 4.25H0V5.75H18V4.25Z" fill="currentColor"/>
		</template>
	</svg>
</template>
