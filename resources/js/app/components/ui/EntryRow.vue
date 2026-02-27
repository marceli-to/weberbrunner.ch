<script setup>
import Cross from '@/components/icons/Cross.vue'
import Eye from '@/components/icons/Eye.vue'
import Pencil from '@/components/icons/Pencil.vue'

defineProps({
	label: String,
	publish: Boolean,
	showPublish: {
		type: Boolean,
		default: true,
	},
})

defineEmits(['edit', 'toggle-publish', 'delete'])
</script>

<template>
	<div class="bg-white text-md min-h-30 border-thin border-black flex justify-between items-center px-20 select-none" :class="{ 'opacity-50': showPublish && !publish }">
		<span>{{ label }}</span>
		<span class="flex gap-x-20">
			<Pencil class="w-14 cursor-pointer" @click="$emit('edit')" />
			<Eye v-if="showPublish" :variant="publish ? 'visible' : 'hidden'" class="w-14 cursor-pointer" @click="$emit('toggle-publish')" />
		</span>
	</div>
</template>
