<script setup>
import Burger from '@/components/icons/Burger.vue'
import Cross from '@/components/icons/Cross.vue'

defineProps({
	item: { type: Object, required: true },
})

defineEmits(['delete'])

const teaser = (item) => item.project?.media?.[0] || null
</script>

<template>
	<div class="border-thin border-black bg-white">

		<div class="relative aspect-square overflow-hidden">
			<div class="absolute z-10 top-20 left-20 right-20 flex items-center justify-between">
				<button type="button" class="landing-drag-handle cursor-grab">
					<Burger variant="sm" class="w-18 h-auto" />
				</button>
				<button type="button" class="cursor-pointer" @click="$emit('delete', item)">
					<Cross class="w-12 h-auto" />
				</button>
			</div>

			<figure class="m-0 w-full h-full flex items-center justify-center px-30 py-60">
				<img
					v-if="teaser(item)"
					:src="teaser(item).preview_url"
					:alt="teaser(item).alt || ''"
					class="block max-w-full max-h-full object-contain" />
			</figure>
		</div>

		<div class="text-center py-5 px-20 text-sm border-t-thin border-t-black overflow-hidden text-ellipsis whitespace-nowrap">
			{{ item.project?.full_title || item.project?.title }}
		</div>

	</div>
</template>
