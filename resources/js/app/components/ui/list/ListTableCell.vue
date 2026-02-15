<script setup>
import Span from '@/components/ui/grid/Span.vue'

const props = defineProps({
	span: { type: Number, default: 1 },
	first: { type: Boolean, default: false },
	last: { type: Boolean, default: false },
	header: { type: Boolean, default: false },
})
</script>

<template>
	<Span
		:class="[
			`col-span-${span}`,
			first ? 'pl-20' : '',
			last ? 'pr-20' : '',
			header ? 'pt-20' : 'pt-6 group-hover:text-white',
		]">

		<span
			class="text-sm font-semibold block w-full border-b border-b-thin"
			:class="[
				header ? 'pb-20' : 'pb-6 border-b-gray group-hover:border-b-navy',
			]">
			<slot />
		</span>
    
	</Span>
</template>
