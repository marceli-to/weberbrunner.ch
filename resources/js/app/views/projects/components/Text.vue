<script setup>
import TextCards from '@/views/projects/components/TextCards.vue'

defineProps({
	project: { type: Object, required: true },
})
</script>

<template>
  <TextCards :project="project" />
</template>
