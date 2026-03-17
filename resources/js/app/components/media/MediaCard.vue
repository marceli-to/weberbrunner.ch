<script setup>
import Burger from '@/components/icons/Burger.vue'
import Cross from '@/components/icons/Cross.vue'
import Eye from '@/components/icons/Eye.vue'

defineProps({
	item: { type: Object, required: true },
	deletable: { type: Boolean, default: false },
	draggable: { type: Boolean, default: false },
	publishable: { type: Boolean, default: false },
	showFilename: { type: Boolean, default: false },
	editable: { type: Boolean, default: false },
	variant: { type: String, default: 'light' },
	compact: { type: Boolean, default: false },
})

defineEmits(['delete', 'edit', 'toggle-publish'])
</script>

<template>
	<div
		class="border-thin bg-white"
		:class="[variant === 'dark' ? 'border-black' : 'border-silver', publishable && !item.publish ? 'opacity-60' : '']">

		<div class="relative aspect-square overflow-hidden">

			<template v-if="draggable || publishable || deletable">
				<div class="absolute z-10 top-20 left-20 right-20 flex items-center justify-between">
					<button v-if="draggable" type="button" class="drag-handle cursor-grab">
						<Burger variant="sm" class="w-18 h-auto" />
					</button>
					<span v-else />
					<button v-if="publishable" type="button" class="cursor-pointer" @click="$emit('toggle-publish', item)">
						<Eye :variant="item.publish ? 'visible' : 'hidden'" class="w-16 h-auto" />
					</button>
					<button v-if="deletable" type="button" class="cursor-pointer" @click="$emit('delete', item)">
						<Cross class="w-12 h-auto" />
					</button>
				</div>
			</template>

			<figure
				class="m-0 w-full h-full"
				:class="editable ? 'cursor-pointer' : ''"
				@click="editable && $emit('edit', item)">
				<div
					class="w-full h-full min-w-0 min-h-0 flex items-center justify-center"
					:class="compact ? 'px-20 py-30' : 'px-30 py-60'">
					<img
						:src="item.preview_url"
						:alt="item.alt || ''"
						class="block max-w-full max-h-full object-contain" />
				</div>
			</figure>


		</div>

		<div
			v-if="showFilename"
			class="text-center py-5 px-20 text-sm border-t-thin overflow-hidden text-ellipsis whitespace-nowrap"
			:class="[variant === 'dark' ? 'border-t-black' : 'border-t-silver', editable ? 'cursor-pointer' : '']"
			@click="editable && $emit('edit', item)">
			{{ item.original_name }}
		</div>
		
	</div>
</template>
