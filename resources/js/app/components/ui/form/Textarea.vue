<script setup>
defineProps({
	modelValue: { type: String, default: '' },
	placeholder: { type: String, default: '' },
	disabled: { type: Boolean, default: false },
})

defineEmits(['update:modelValue'])
</script>

<template>
	<textarea
		:value="modelValue"
		:placeholder="placeholder"
		:disabled="disabled"
		@input="$emit('update:modelValue', $event.target.value)"
	/>
</template>
