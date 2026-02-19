<template>
	<div class="bg-white min-h-50 flex items-center px-20">
		<component :is="`h${level}`" class="text-lg font-semibold">
			<slot />
		</component>
	</div>
</template>
<script setup>
defineProps({
	level: {
		type: [Number, String],
		default: 2,
	},
});
</script>
