<script setup>
defineProps({
	modelValue: { type: [String, Number], default: '' },
	value: { type: [String, Number], required: true },
	label: { type: String, default: '' },
	name: { type: String, default: '' },
	disabled: { type: Boolean, default: false },
})

defineEmits(['update:modelValue'])
</script>

<template>
	<label>
		<input
			type="radio"
			:name="name"
			:value="value"
			:checked="modelValue === value"
			:disabled="disabled"
			@change="$emit('update:modelValue', value)"
		/>
		<span v-if="label">{{ label }}</span>
	</label>
</template>
