<script setup>
import awardsApi from '@/api/awards'
import SectionGroupedIndex from '@/components/ui/SectionGroupedIndex.vue'
</script>

<template>
	<SectionGroupedIndex
		page-title="Auszeichnungen"
		:api="awardsApi"
		section-type="award"
		route-prefix="awards"
		collapsed-key="awards" />
</template>
