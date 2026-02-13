<script setup>
defineProps({
	modelValue: { type: Boolean, default: false },
	label: { type: String, default: '' },
	disabled: { type: Boolean, default: false },
})

defineEmits(['update:modelValue'])
</script>

<template>
	<label>
		<input
			type="checkbox"
			:checked="modelValue"
			:disabled="disabled"
			@change="$emit('update:modelValue', $event.target.checked)"
		/>
		<span v-if="label">{{ label }}</span>
	</label>
</template>
