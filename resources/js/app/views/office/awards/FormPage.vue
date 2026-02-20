<script setup>
import awardsApi from '@/api/awards'
import SectionEntryForm from '@/components/ui/SectionEntryForm.vue'
</script>

<template>
	<SectionEntryForm
		:api="awardsApi"
		page-title="Auszeichnungen"
		back-route="office.awards" />
</template>
