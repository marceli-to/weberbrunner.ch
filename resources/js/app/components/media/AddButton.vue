<script setup>
import PlusCircle from '@/components/icons/PlusCircle.vue'

defineProps({
	label: { type: String, default: 'Hinzufügen' },
})
</script>

<template>
	<button
		type="button"
		class="border-thin border-silver bg-white flex justify-center p-10 w-full aspect-square cursor-pointer relative">
		<span class="font-semibold block">{{ label }}</span>
		<span class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
			<PlusCircle class="w-25" />
		</span>
	</button>
</template>
