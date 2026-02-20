<script setup>
import LinkExternal from '@/components/icons/LinkExternal.vue'

defineProps({
  slug: {
    type: String,
    default: null
  }
})
</script>

<template>
  <div class="bg-white min-h-50 flex items-center px-20" :class="{ 'justify-between': slug }">
    <h1 class="text-lg font-semibold">
      <slot />
    </h1>
    <a 
      v-if="slug" 
      :href="`/vorschau/${slug}`" 
      target="_blank" 
      aria-label="Projekt auf der Website anzeigen">
      <LinkExternal class="w-24 h-auto" />
    </a>
  </div>
</template>
