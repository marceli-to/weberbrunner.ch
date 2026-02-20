<script setup>
import { ref } from 'vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import Burger from '@/components/icons/Burger.vue'
import CollapsibleHeader from '@/components/ui/CollapsibleHeader.vue'
import Cross from '@/components/icons/Cross.vue'

const props = defineProps({
	block: { type: Object, required: true },
})

defineEmits(['delete'])

const typeLabels = {
	text: 'Text',
	image: 'Einzelbild',
	slider: 'Slider',
	links: 'Links',
}

const collapsed = ref(true)
</script>

<template>
	<Grid :cols="10">
		<Span class="col-span-1 flex items-start justify-end pt-20">
			<Burger variant="sm" class="w-18 h-10 cursor-grab drag-handle" />
		</Span>
		<Span class="col-span-8">
			<CollapsibleHeader
				:title="block.title || typeLabels[block.type]"
				:collapsed="collapsed"
				@toggle="collapsed = !collapsed" />
			<div v-show="!collapsed" class="bg-white px-20 pb-20">
				<slot />
			</div>
		</Span>
		<Span class="col-span-1 flex items-start justify-start pt-20">
			<Cross class="w-10 cursor-pointer" @click="$emit('delete')" />
		</Span>
	</Grid>
</template>
