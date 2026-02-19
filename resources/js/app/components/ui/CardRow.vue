<script setup>
defineProps({
	header: { type: Boolean, default: false },
})
</script>

<template>
	<div
		class="text-md flex items-center border-b-thin"
		:class="header ? 'font-semibold min-h-50' : 'min-h-30 border-b-gray'">
		<slot />
	</div>
</template>
