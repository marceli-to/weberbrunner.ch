<script setup>
import CheckboxIcon from '@/components/icons/Checkbox.vue'

defineProps({
	modelValue: { type: Boolean, default: false },
	label: { type: String, default: '' },
	disabled: { type: Boolean, default: false },
})

defineEmits(['update:modelValue'])
</script>

<template>
	<label class="flex items-center gap-x-10 cursor-pointer" :class="{ 'opacity-50': disabled }">
		<input
			type="checkbox"
			:checked="modelValue"
			:disabled="disabled"
			class="hidden"
			@change="$emit('update:modelValue', $event.target.checked)"
		/>
		<CheckboxIcon :variant="modelValue ? 'checked' : 'unchecked'" class="w-12 h-12 shrink-0" />
		<span v-if="label" class="font-semibold">{{ label }}</span>
	</label>
</template>
