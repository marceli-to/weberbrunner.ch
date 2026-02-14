<script setup>
import juryApi from '@/api/jury'
import SectionGroupedIndex from '@/components/ui/SectionGroupedIndex.vue'
</script>

<template>
	<SectionGroupedIndex
		page-title="Jury"
		:api="juryApi"
		section-type="jury"
		route-prefix="jury"
		collapsed-key="jury" />
</template>
