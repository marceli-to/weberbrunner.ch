<script setup>
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import Burger from '@/components/icons/Burger.vue'
import Cross from '@/components/icons/Cross.vue'
import EntryRow from '@/components/ui/EntryRow.vue'

defineProps({
	label: String,
	publish: Boolean,
	showPublish: {
		type: Boolean,
		default: true,
	},
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
			<EntryRow
				:label="label"
				:publish="publish"
				:show-publish="showPublish"
				@edit="$emit('edit')"
				@toggle-publish="$emit('toggle-publish')" />
		</Span>
		<Span class="col-span-1 flex items-center justify-start">
			<Cross class="w-10 cursor-pointer" @click="$emit('delete')" />
		</Span>
	</Grid>
</template>
