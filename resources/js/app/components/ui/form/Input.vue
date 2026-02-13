<script setup>
defineProps({
	type: { type: String, default: 'text' },
	modelValue: { type: [String, Number], default: '' },
	placeholder: { type: String, default: '' },
	disabled: { type: Boolean, default: false },
})

defineEmits(['update:modelValue'])
</script>

<template>
	<input
		:type="type"
		:value="modelValue"
		:placeholder="placeholder"
		:disabled="disabled"
		@input="$emit('update:modelValue', $event.target.value)"
	/>
</template>
