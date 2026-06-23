<script setup>
import { ref } from 'vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import Burger from '@/components/icons/Burger.vue'
import CollapsibleHeader from '@/components/ui/CollapsibleHeader.vue'
import Cross from '@/components/icons/Cross.vue'

const props = defineProps({
	block: { type: Object, required: true },
	initialOpen: { type: Boolean, default: false },
	flush: { type: Boolean, default: false },
	editable: { type: Boolean, default: false },
	draggable: { type: Boolean, default: true },
	deletable: { type: Boolean, default: true },
})

defineEmits(['delete', 'edit-title'])

const typeLabels = {
	text: 'Text',
	image: 'Einzelbild',
	slider: 'Slider',
	links: 'Links',
	download: 'Download',
	link: 'Link',
}

const collapsed = ref(!props.initialOpen)
</script>

<template>
	<Grid :cols="10">
		<Span class="col-span-1 flex items-start justify-end pt-20">
			<template v-if="draggable">
				<Burger variant="sm" class="w-18 h-10 cursor-grab drag-handle" />
			</template>
		</Span>
		<Span class="col-span-8">
			<CollapsibleHeader
				:title="block.title || typeLabels[block.type]"
				:collapsed="collapsed"
				:editable="editable"
				@toggle="collapsed = !collapsed"
				@edit="$emit('edit-title', block)" />
			<div v-if="!flush" v-show="!collapsed" class="bg-white px-20 pb-20">
				<slot />
			</div>
		</Span>
		<Span class="col-span-1 flex items-start justify-start pt-20">
			<template v-if="deletable">
				<Cross class="w-10 cursor-pointer" @click="$emit('delete')" />
			</template>
		</Span>
		<Span v-if="flush" v-show="!collapsed" class="col-span-10">
			<slot />
		</Span>
	</Grid>
</template>
