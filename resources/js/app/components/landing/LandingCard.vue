<script setup>
import Burger from '@/components/icons/Burger.vue'
import Cross from '@/components/icons/Cross.vue'

defineProps({
	item: { type: Object, required: true },
	draggable: {
		type: Boolean,
		default: true,
	},
	deletable: {
		type: Boolean,
		default: true,
	},
})

defineEmits(['delete'])

const teaser = (item) => item.project?.media?.[0] || null
</script>

<template>
	<div class="border-thin border-black bg-white">

		<div class="relative aspect-square overflow-hidden">
			<div class="absolute z-10 top-20 left-20 right-20 flex items-center justify-between">
				<button v-if="draggable" type="button" class="landing-drag-handle cursor-grab">
					<Burger variant="sm" class="w-18 h-auto" />
				</button>
				<button v-if="deletable" type="button" class="cursor-pointer" @click="$emit('delete', item)">
					<Cross class="w-12 h-auto" />
				</button>
			</div>

			<figure class="w-full h-full">
				<div class="w-full h-full min-w-0 min-h-0 flex items-center justify-center px-10 py-30 md:px-20 md:py-40 lg:px-30 lg:py-60">
					<img
						v-if="teaser(item)"
						:src="teaser(item).preview_url"
						:alt="teaser(item).alt || ''"
						class="block max-w-full max-h-full object-contain"
					/>
				</div>
			</figure>


		</div>

		<div class="text-center py-5 px-20 text-sm border-t-thin border-t-black overflow-hidden text-ellipsis whitespace-nowrap">
			{{ item.project?.full_title || item.project?.title }}
		</div>

	</div>
</template>
