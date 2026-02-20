<script setup>
import PlusCircle from '@/components/icons/PlusCircle.vue'
import BlockText from '@/components/icons/BlockText.vue'
import BlockImage from '@/components/icons/BlockImage.vue'
import BlockGallery from '@/components/icons/BlockGallery.vue'
import BlockLink from '@/components/icons/BlockLink.vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'

const emit = defineEmits(['select'])

const blockTypes = [
	{ type: 'text', label: 'Text', icon: { component: BlockText, class: 'w-auto h-40', wrapperClass: 'flex justify-center' } },
	{ type: 'slider', label: 'Slider', icon: { component: BlockGallery, class: 'w-auto h-40', wrapperClass: 'flex justify-center' } },
	{ type: 'image', label: 'Bild', icon: { component: BlockImage, class: 'w-auto h-40', wrapperClass: 'flex justify-center' } },
	{ type: 'links', label: 'Link', icon: { component: BlockLink, class: 'w-auto h-40', wrapperClass: 'flex justify-center' } },
]
</script>

<template>

  <Grid :cols="4">

    <Span class="col-span-1" v-for="bt in blockTypes" :key="bt.type">

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
