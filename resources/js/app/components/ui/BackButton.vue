<script setup>
import Arrow from '@/components/icons/Arrow.vue'

defineEmits(['click'])
</script>

<template>
	<button type="button" @click="$emit('click')">
		<Arrow variant="left" class="w-25 cursor-pointer" />
	</button>
</template>
