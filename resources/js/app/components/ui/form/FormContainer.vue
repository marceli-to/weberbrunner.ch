<script setup>
defineEmits(['submit'])
</script>

<template>
	<form class="flex flex-col min-h-screen" @submit.prevent="$emit('submit')">
		<slot />
	</form>
</template>
