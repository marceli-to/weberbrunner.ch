<script setup>
import Span from '@/components/ui/grid/Span.vue'
import Chevron from '@/components/icons/Chevron.vue'

const props = defineProps({
	span: { type: String, default: 'col-span-1' },
	first: { type: Boolean, default: false },
	last: { type: Boolean, default: false },
	header: { type: Boolean, default: false },
	sortable: { type: Boolean, default: false },
	sortActive: { type: Boolean, default: false },
	sortDir: { type: String, default: 'asc' },
})

const emit = defineEmits(['sort'])
</script>

<template>
	<Span
		:class="[
			span,
			first ? 'pl-20' : '',
			last ? 'pr-20' : '',
			header ? 'pt-20' : 'group-hover:text-white',
		]">

		<span
			class="block w-full border-b-thin"
			:class="[
				header ? 'pb-20' : 'min-h-30 flex items-center border-b-gray group-hover:border-b-navy',
			]">
			<button
				v-if="sortable && header"
				type="button"
				class="flex items-center gap-6 cursor-pointer hover:text-navy"
				@click="emit('sort')">
				<slot />
				<Chevron v-if="sortActive" :variant="sortDir === 'asc' ? 'up' : 'down'" size="sm" class="w-10 h-auto mt-2" />
			</button>
			<slot v-else />
		</span>

	</Span>
</template>
