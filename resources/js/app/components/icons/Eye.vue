<script setup>
defineOptions({ inheritAttrs: false })
defineProps({
	variant: { type: String, default: 'visible', validator: (v) => ['visible', 'hidden'].includes(v) }
})
</script>

<template>
	<div v-bind="$attrs" class="flex items-center justify-center" style="aspect-ratio: 1/1;">
		<svg v-if="variant === 'hidden'" class="w-full h-full" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path d="M11.63 6.84C11.7 7.13 11.75 7.44 11.75 7.75C11.75 9.96 9.96 11.75 7.75 11.75C7.43 11.75 7.12997 11.71 6.83997 11.63L5.59998 12.87C6.26998 13.1 6.98001 13.24 7.76001 13.24C11.21 13.24 13.53 10.73 15.26 7.73999C14.7 6.77999 14.09 5.86999 13.39 5.07999L11.64 6.82999L11.63 6.84Z" fill="currentColor"/>
			<path d="M7.75 3.75998C8.71 3.75998 9.58002 4.10999 10.27 4.67999L8.84998 6.10001C8.52998 5.89001 8.15 5.76999 7.75 5.76999C6.65 5.76999 5.75 6.65999 5.75 7.76999C5.75 8.17999 5.86997 8.56 6.08997 8.87L4.66998 10.29C4.09998 9.60001 3.75 8.72999 3.75 7.76999C3.75 5.55999 5.54 3.76999 7.75 3.76999M1.06 16L16 1.06L14.94 0L11.52 3.41998C10.43 2.68998 9.19 2.25 7.75 2.25C4.3 2.25 1.98 4.76 0.25 7.75C1.11 9.24 2.12002 10.6 3.33002 11.6L0 14.93L1.06 15.99V16Z" fill="currentColor"/>
		</svg>
		<svg v-else class="w-full" viewBox="0 0 15 11" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path d="M7.5 3.5C6.4 3.5 5.5 4.39 5.5 5.5C5.5 6.61 6.4 7.5 7.5 7.5C8.6 7.5 9.5 6.61 9.5 5.5C9.5 4.39 8.6 3.5 7.5 3.5Z" fill="currentColor"/>
			<path d="M7.5 9.5C5.29 9.5 3.5 7.71 3.5 5.5C3.5 3.29 5.29 1.5 7.5 1.5C9.71 1.5 11.5 3.29 11.5 5.5C11.5 7.71 9.71 9.5 7.5 9.5ZM7.5 0C4.05 0 1.73 2.51 0 5.5C1.73 8.49 4.05 11 7.5 11C10.95 11 13.27 8.49 15 5.5C13.27 2.51 10.95 0 7.5 0Z" fill="currentColor"/>
		</svg>
	</div>
</template>
