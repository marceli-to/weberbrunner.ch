<template>
	<svg v-if="variant === 'filled'" width="19" height="18" viewBox="0 0 19 18" fill="none" xmlns="http://www.w3.org/2000/svg">
		<path d="M18.93 6.88H11.7L9.45996 0L7.22998 6.88H0L5.84998 11.13L3.62 18L9.45996 13.75L15.31 18L13.08 11.13L18.93 6.88Z" fill="currentColor"/>
	</svg>
	<svg v-else width="19" height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
		<path d="M9.45996 4.21002L10.75 8.17999H14.92L11.54 10.63L11.83 11.53L12.83 14.6L9.44995 12.15L6.06995 14.6L7.35999 10.63L3.97998 8.17999H8.14996L9.44 4.21002M9.45996 0L7.22998 6.88H0L5.84998 11.13L3.62 18.01L9.46997 13.76L15.3199 18.01L13.09 11.13L18.94 6.88H11.71L9.47998 0H9.45996Z" fill="currentColor"/>
	</svg>
</template>

<script setup>
defineProps({
	variant: {
		type: String,
		default: 'outline',
	},
});
</script>
