<script setup>
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import Burger from '@/components/icons/Burger.vue'
import Cross from '@/components/icons/Cross.vue'
import Eye from '@/components/icons/Eye.vue'
import Pencil from '@/components/icons/Pencil.vue'

defineProps({
	label: String,
	publish: Boolean,
	dragHandleClass: String,
})

defineEmits(['edit', 'toggle-publish', 'delete'])
</script>

<template>
	<Grid :cols="10">
		<Span class="col-span-1 flex items-center justify-end">
			<Burger variant="sm" class="w-18 h-10 cursor-grab" :class="dragHandleClass" />
		</Span>
		<Span class="col-span-8">
			<div class="bg-white text-md min-h-30 border-thin border-black flex justify-between items-center px-20 select-none" :class="{ 'opacity-50': !publish }">
				<span>{{ label }}</span>
				<span class="flex gap-x-20">
					<Pencil class="w-14 cursor-pointer" @click="$emit('edit')" />
					<Eye :variant="publish ? 'visible' : 'hidden'" class="w-14 cursor-pointer" @click="$emit('toggle-publish')" />
				</span>
			</div>
		</Span>
		<Span class="col-span-1 flex items-center justify-start">
			<Cross class="w-10 cursor-pointer" @click="$emit('delete')" />
		</Span>
	</Grid>
</template>
