<script setup>
import PlusCircle from '@/components/icons/PlusCircle.vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'

defineProps({
	types: { type: Array, required: true },
	cols: { type: Number, default: 4 },
})

const emit = defineEmits(['select'])
</script>

<template>

	<Grid :cols="cols">

		<Span class="col-span-1" v-for="bt in types" :key="bt.type">

			<button
				type="button"
				class="bg-navy text-slate hover:text-white transition-colors duration-100 text-md cursor-pointer w-full"
				@click="emit('select', bt.type)">

				<span class="block border-b-thin border-slate py-10">
					{{ bt.label }}
				</span>

				<span class="block py-40" :class="bt.icon.wrapperClass">
					<component :is="bt.icon.component" :class="bt.icon.class" />
				</span>

				<span class="border-t-thin border-slate py-10 flex justify-center">
					<PlusCircle class="w-20" />
				</span>

			</button>

		</Span>

	</Grid>

</template>
