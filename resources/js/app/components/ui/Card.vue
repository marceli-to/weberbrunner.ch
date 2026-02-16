<script setup>
defineProps({
	hasHeader: { type: Boolean, default: false },
})
</script>

<template>
	<div class="bg-white" :class="hasHeader ? 'px-20 pb-20' : 'p-20'">
		<slot />
	</div>
</template>
