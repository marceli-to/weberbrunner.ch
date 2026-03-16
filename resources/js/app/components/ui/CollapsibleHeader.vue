<script setup>
import Chevron from '@/components/icons/Chevron.vue'
import PencilCircle from '@/components/icons/PencilCircle.vue'

defineProps({
	title: String,
	collapsed: Boolean,
	editable: Boolean,
})

defineEmits(['toggle', 'edit'])
</script>

<template>
	<div
		class="bg-white text-lg font-semibold min-h-50 flex justify-between items-center px-20 select-none">
		<component
			:is="editable ? 'button' : 'div'"
			class="group/title flex items-center gap-10"
			:class="{ 'cursor-pointer': editable }"
			@click="editable && $emit('edit')">
			<span><slot>{{ title }}</slot></span>
			<PencilCircle
				v-if="editable"
				class="w-18 h-18 opacity-0 group-hover/title:opacity-100 transition-opacity" />
		</component>
		<button @click="$emit('toggle')" class="flex items-center justify-center cursor-pointer min-h-50 min-w-50">
			<Chevron :variant="collapsed ? 'down' : 'up'" size="lg" class="w-20" />
		</button>
	</div>
</template>
