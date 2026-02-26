<script setup>
import RadioIcon from '@/components/icons/Radio.vue'

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
	<label class="flex items-center gap-x-10 cursor-pointer" :class="{ 'opacity-50': disabled }">
		<input
			type="radio"
			:name="name"
			:value="value"
			:checked="modelValue === value"
			:disabled="disabled"
			class="hidden"
			@change="$emit('update:modelValue', value)"
		/>
		<RadioIcon :variant="modelValue === value ? 'checked' : 'unchecked'" class="w-12 h-12 shrink-0" />
		<span v-if="label" class="font-semibold">{{ label }}</span>
	</label>
</template>
