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
		class="bg-white text-lg font-semibold min-h-50 flex justify-between items-center px-20 select-none">
		<span>
			<slot>{{ title }}</slot>
		</span>
    <button @click="$emit('toggle')" class="flex items-center justify-center cursor-pointer min-h-50 min-w-50">
  		<Chevron :variant="collapsed ? 'down' : 'up'" size="lg" class="w-20" />
    </button>
	</div>
</template>
