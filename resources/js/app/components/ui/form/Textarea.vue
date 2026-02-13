<script setup>
defineProps({
	modelValue: { type: String, default: '' },
	placeholder: { type: String, default: '' },
	disabled: { type: Boolean, default: false },
	error: { type: String, default: null },
})

defineEmits(['update:modelValue'])
</script>

<template>
	<textarea
		:value="modelValue"
		:placeholder="error || placeholder"
		:disabled="disabled"
		:class="{ 'has-error': error }"
		@input="$emit('update:modelValue', $event.target.value)"
	/>
</template>
