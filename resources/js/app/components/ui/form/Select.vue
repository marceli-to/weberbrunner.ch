<script setup>
defineProps({
	modelValue: { type: [String, Number], default: '' },
	options: { type: Array, default: () => [] },
	disabled: { type: Boolean, default: false },
})

defineEmits(['update:modelValue'])
</script>

<template>
	<select
		:value="modelValue"
		:disabled="disabled"
		@change="$emit('update:modelValue', $event.target.value)"
	>
		<slot>
			<option
				v-for="option in options"
				:key="option.value"
				:value="option.value"
			>
				{{ option.label }}
			</option>
		</slot>
	</select>
</template>
