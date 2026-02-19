<script setup>
defineProps({
	header: { type: Boolean, default: false },
})
</script>

<template>
	<div class="bg-white" :class="header ? 'px-20 pb-20' : 'p-20'">
		<slot />
	</div>
</template>
