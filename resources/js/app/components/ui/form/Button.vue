<script setup>
import { computed } from 'vue'

const props = defineProps({
	variant: { type: String, default: 'primary' },
	size: { type: String, default: 'base' },
	disabled: { type: Boolean, default: false },
	as: { type: [String, Object], default: 'button' },
})

const variants = {
	primary: 'bg-black text-white hover:bg-gray',
	secondary: 'bg-snow text-black hover:bg-silver',
	outline: 'border border-black text-black hover:bg-snow',
	danger: 'bg-red text-white hover:bg-wine',
	ghost: 'text-black hover:bg-snow',
}

const sizes = {
	sm: 'px-12 py-6 text-sm',
	base: 'px-16 py-12 text-sm',
	lg: 'px-20 py-14 text-md',
}

const classes = computed(() => [
	'inline-flex items-center font-semibold transition-colors cursor-pointer',
	variants[props.variant],
	sizes[props.size],
	props.disabled && 'opacity-40 pointer-events-none',
])

const justification = computed(() => {
	const hasLeft = !!$slots['icon-left']
	const hasRight = !!$slots['icon-right']
	if (hasLeft && hasRight) return 'justify-between'
	if (hasRight) return 'justify-between'
	return 'gap-8'
})

const $slots = defineSlots()
</script>

<template>
	<component
		:is="as"
		:class="[classes, justification]"
		:disabled="disabled"
		type="button"
	>
		<span v-if="$slots['icon-left']" class="shrink-0">
			<slot name="icon-left" />
		</span>
		<span><slot /></span>
		<span v-if="$slots['icon-right']" class="shrink-0">
			<slot name="icon-right" />
		</span>
	</component>
</template>
