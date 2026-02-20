<script setup>
import { useProject } from '@/composables/useProject'
import WebPageLayout from '@/views/projects/components/WebPageLayout.vue'

const { project } = useProject()
</script>

<template>
	<WebPageLayout :project="project" />
</template>
