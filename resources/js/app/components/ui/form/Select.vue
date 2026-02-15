<script setup>
defineProps({
	modelValue: { type: [String, Number], default: '' },
	options: { type: Array, default: () => [] },
	disabled: { type: Boolean, default: false },
	error: { type: String, default: null },
})

defineEmits(['update:modelValue'])
</script>

<template>
	<select
		:value="modelValue"
		:disabled="disabled"
		class="form-input form-input--sm form-select"
		:class="{ 'has-error': error }"
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
