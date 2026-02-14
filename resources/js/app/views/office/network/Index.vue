<script setup>
import networkApi from '@/api/network'
import SectionGroupedIndex from '@/components/ui/SectionGroupedIndex.vue'
</script>

<template>
	<SectionGroupedIndex
		page-title="Netzwerk"
		:api="networkApi"
		section-type="network"
		route-prefix="network"
		collapsed-key="network" />
</template>
