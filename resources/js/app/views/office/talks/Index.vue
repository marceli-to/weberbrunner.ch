<script setup>
import talksApi from '@/api/talks'
import SectionGroupedIndex from '@/components/ui/SectionGroupedIndex.vue'
</script>

<template>
	<SectionGroupedIndex
		page-title="Vorträge"
		:api="talksApi"
		section-type="talk"
		route-prefix="talks"
		collapsed-key="talks" />
</template>
