<script setup>
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import Burger from '@/components/icons/Burger.vue'
import Cross from '@/components/icons/Cross.vue'
import EntryRow from '@/components/ui/EntryRow.vue'

defineProps({
	label: String,
	sublabel: String,
	split: {
		type: Boolean,
		default: false,
	},
	publish: Boolean,
	showPublish: {
		type: Boolean,
		default: true,
	},
	editable: {
		type: Boolean,
		default: true,
	},
	standard: Boolean,
	showDefault: {
		type: Boolean,
		default: false,
	},
	dragHandleClass: String,
	draggable: {
		type: Boolean,
		default: true,
	},
	deletable: {
		type: Boolean,
		default: true,
	},
})

defineEmits(['edit', 'toggle-publish', 'toggle-default', 'delete'])
</script>

<template>
	<Grid :cols="10">
		<Span class="col-span-1 flex items-center justify-end">
			<template v-if="draggable">
				<Burger variant="sm" class="w-18 h-10 cursor-grab" :class="dragHandleClass" />
			</template>
		</Span>
		<Span class="col-span-8">
			<EntryRow
				:label="label"
				:sublabel="sublabel"
				:split="split"
				:publish="publish"
				:show-publish="showPublish"
				:editable="editable"
				:standard="standard"
				:show-default="showDefault"
				@edit="$emit('edit')"
				@toggle-publish="$emit('toggle-publish')"
				@toggle-default="$emit('toggle-default')" />
		</Span>
		<Span class="col-span-1 flex items-center justify-start">
			<template v-if="deletable">
				<Cross class="w-10 cursor-pointer" @click="$emit('delete')" />
			</template>
		</Span>
	</Grid>
</template>
