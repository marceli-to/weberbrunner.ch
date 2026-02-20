<script setup>
import { useProject } from '@/composables/useProject'
import WebLayout from '@/views/projects/components/WebLayout.vue'

const { project } = useProject()
</script>

<template>
	<WebLayout :project="project" />
</template>
