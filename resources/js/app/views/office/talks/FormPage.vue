<script setup>
import talksApi from '@/api/talks'
import SectionEntryForm from '@/components/ui/SectionEntryForm.vue'
</script>

<template>
	<SectionEntryForm
		:api="talksApi"
		page-title="Vorträge"
		back-route="office.talks" />
</template>
