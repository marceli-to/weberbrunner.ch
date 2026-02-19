<template>
	<component :is="`h${level}`" class="text-lg font-semibold">
		<slot />
	</component>
</template>
<script setup>
defineProps({
	level: {
		type: [Number, String],
		default: 2,
	},
});
</script>
