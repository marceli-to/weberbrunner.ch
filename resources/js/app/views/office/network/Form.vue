<script setup>
import networkApi from '@/api/network'
import SectionEntryForm from '@/components/ui/SectionEntryForm.vue'
</script>

<template>
	<SectionEntryForm
		:api="networkApi"
		page-title="Netzwerk"
		back-route="office.network" />
</template>
