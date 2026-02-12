<script setup>
import PageTitle from '@/components/ui/PageTitle.vue'
</script>

<template>

	<div class="grid grid-cols-10 gap-20 mb-20">
		<PageTitle class="col-span-8 col-start-2">
      Auszeichnungen
    </PageTitle>
	</div>

  <div class="grid grid-cols-10 gap-20">
    <div class="col-span-1 min-h-100 bg-blue-500 flex items-center justify-end">
      [drag]
    </div>
    <div class="col-span-8 min-h-100 bg-green-500"></div>
    <div class="col-span-1 min-h-100 bg-yellow-500 flex items-center justify-start">
      [delete]
    </div>
  </div>
</template>
