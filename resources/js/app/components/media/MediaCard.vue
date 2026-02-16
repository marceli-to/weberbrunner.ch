<script setup>
import Cross from '@/components/icons/Cross.vue'

defineProps({
	item: { type: Object, required: true },
	deletable: { type: Boolean, default: false },
	showFilename: { type: Boolean, default: false },
})

defineEmits(['delete'])
</script>

<template>
	<div class="border-thin border-silver bg-white">
		<div class="relative p-20 flex items-center justify-center aspect-square">
			<button
				v-if="deletable"
				type="button"
				class="absolute top-10 right-10 cursor-pointer"
				@click="$emit('delete', item)">
				<Cross class="w-12 h-auto" />
			</button>
			<img
				:src="item.preview_url"
				:alt="item.alt || ''"
				class="block max-w-full max-h-full object-contain" />
		</div>
		<div v-if="showFilename" class="text-center py-5 px-20 text-sm border-t-thin border-t-silver overflow-hidden text-ellipsis whitespace-nowrap">
			{{ item.original_name }}
		</div>
	</div>
</template>
