<script setup>
defineEmits(['submit'])
</script>

<template>
	<form class="flex flex-col flex-1 min-h-0" @submit.prevent="$emit('submit')">
		<slot />
	</form>
</template>
