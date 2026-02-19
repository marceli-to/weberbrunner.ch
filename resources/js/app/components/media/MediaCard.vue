<script setup>
import Burger from '@/components/icons/Burger.vue'
import Cross from '@/components/icons/Cross.vue'

defineProps({
	item: { type: Object, required: true },
	deletable: { type: Boolean, default: false },
	draggable: { type: Boolean, default: false },
	showFilename: { type: Boolean, default: false },
	editable: { type: Boolean, default: false },
	variant: { type: String, default: 'light' },
})

defineEmits(['delete', 'edit'])
</script>

<template>
	<div class="border-thin bg-white" :class="variant === 'dark' ? 'border-black' : 'border-silver'">

		<div class="relative px-40 py-60 flex items-center justify-center aspect-square">

      <template v-if="draggable || deletable">
        <div class="absolute top-20 left-20 right-20 flex items-center justify-between">
          <button v-if="draggable" type="button" class="drag-handle cursor-grab">
            <Burger variant="sm" class="w-18 h-auto" />
          </button>
          <span v-else />
          <button v-if="deletable" type="button" class="cursor-pointer" @click="$emit('delete', item)">
            <Cross class="w-12 h-auto" />
          </button>
        </div>
      </template>

			<figure :class="{ 'cursor-pointer': editable }" @click="editable && $emit('edit', item)">
				<img
					:src="item.preview_url"
					:alt="item.alt || ''"
					class="block max-w-full max-h-full object-contain" />
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
