<script setup>
import Chevron from '@/components/icons/Chevron.vue'

defineProps({
	title: String,
	collapsed: Boolean,
})

defineEmits(['toggle'])
</script>

<template>
	<div
		class="bg-white text-lg font-semibold min-h-50 flex justify-between items-center px-20 cursor-pointer select-none"
		@click="$emit('toggle')">
		<span>
			<slot>{{ title }}</slot>
		</span>
		<Chevron :variant="collapsed ? 'down' : 'up'" size="lg" class="w-20" />
	</div>
</template>
