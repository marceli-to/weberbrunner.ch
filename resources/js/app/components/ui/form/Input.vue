<script setup>
defineProps({
	type: { type: String, default: 'text' },
	modelValue: { type: [String, Number], default: '' },
	placeholder: { type: String, default: '' },
	disabled: { type: Boolean, default: false },
	error: { type: String, default: null },
})

defineEmits(['update:modelValue'])
</script>

<template>
	<input
		:type="type"
		:value="modelValue"
		:placeholder="error || placeholder"
		:disabled="disabled"
		:class="{ 'has-error': error }"
		@input="$emit('update:modelValue', $event.target.value)"
	/>
</template>
