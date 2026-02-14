<script setup>
import juryApi from '@/api/jury'
import SectionEntryForm from '@/components/ui/SectionEntryForm.vue'
</script>

<template>
	<SectionEntryForm
		:api="juryApi"
		page-title="Jury"
		back-route="office.jury" />
</template>
