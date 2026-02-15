<script setup>
import Span from '@/components/ui/grid/Span.vue'

const props = defineProps({
	span: { type: String, default: 'col-span-1' },
	first: { type: Boolean, default: false },
	last: { type: Boolean, default: false },
	header: { type: Boolean, default: false },
})
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
			<slot />
		</span>
    
	</Span>
</template>
