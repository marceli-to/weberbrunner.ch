<script setup>
defineOptions({ inheritAttrs: false })
defineProps({
	variant: { type: String, default: 'unchecked', validator: (v) => ['unchecked', 'checked', 'unchecked-thin', 'checked-thin'].includes(v) }
})
</script>

<template>
	<svg v-if="variant === 'checked-thin'" v-bind="$attrs" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
		<path d="M0 0V12H12V0H0ZM10.8 10.8H1.20001V1.20001H10.8V10.8Z" fill="currentColor"/>
		<path d="M3.33997 9.5L6 6.85004L8.64996 9.5L9.5 8.65002L6.83997 6L9.5 3.35004L8.64996 2.5L6 5.15002L3.33997 2.5L2.5 3.35004L5.14996 6L2.5 8.65002L3.33997 9.5Z" fill="currentColor"/>
	</svg>
	<svg v-else-if="variant === 'unchecked-thin'" v-bind="$attrs" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
		<path d="M0 0V12H12V0H0ZM10.8 10.8H1.20001V1.20001H10.8V10.8Z" fill="currentColor"/>
	</svg>
	<svg v-else-if="variant === 'checked'" v-bind="$attrs" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
		<path d="M0 0V12H12V0H0ZM10.5 10.5H1.5V1.5H10.5V10.5Z" fill="currentColor"/>
		<path d="M3.33997 9.5L6 6.85004L8.64996 9.5L9.5 8.65002L6.83997 6L9.5 3.35004L8.64996 2.5L6 5.15002L3.33997 2.5L2.5 3.35004L5.14996 6L2.5 8.65002L3.33997 9.5Z" fill="currentColor"/>
	</svg>
	<svg v-else v-bind="$attrs" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
		<path d="M0 0V12H12V0H0ZM10.5 10.5H1.5V1.5H10.5V10.5Z" fill="currentColor"/>
	</svg>
</template>
