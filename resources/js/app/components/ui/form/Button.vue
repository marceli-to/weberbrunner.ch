<script setup>
defineProps({
	disabled: { type: Boolean, default: false },
	type: { type: String, default: 'button' },
	as: { type: [String, Object], default: 'button' },
	variant: { type: String, default: 'default' },
})

const $slots = defineSlots()

const variantClasses = {
	default: '',
	primary: 'form-button--primary',
	secondary: 'form-button--secondary',
	danger: 'form-button--danger',
	'danger-outline': 'form-button--danger-outline',
}
</script>

<template>

	<component
		:is="as"
		class="form-button"
		:class="[variantClasses[variant], { 'justify-between': $slots['icon-right'] }]"
		:disabled="disabled"
		:type="type">

		<span v-if="$slots['icon-left']" class="shrink-0">
			<slot name="icon-left" />
		</span>

		<span>
			<slot />
		</span>

		<span v-if="$slots['icon-right']" class="shrink-0">
			<slot name="icon-right" />
		</span>

	</component>

</template>
