<script setup>
import Input from '@/components/ui/form/Input.vue'
import Cross from '@/components/icons/Cross.vue'

defineProps({
	label: { type: String, required: true },
	modelValue: { type: String, default: '' },
})

defineEmits(['update:modelValue'])
</script>

<template>
	<div class="grid grid-cols-12 gap-x-10 select-none">
		<div class="col-span-4 bg-white text-md min-h-30 border-thin border-black flex items-center px-20 flex-1">
			<span>{{ label }}</span>
		</div>
		<div class="col-span-8 flex-1 relative">
			<Input
				:model-value="modelValue"
				@update:model-value="$emit('update:modelValue', $event)" />
			<button
				v-if="modelValue"
				type="button"
				class="absolute right-10 top-1/2 -translate-y-1/2 cursor-pointer"
				@click="$emit('update:modelValue', '')">
				<Cross class="w-10" />
			</button>
		</div>
	</div>
</template>
