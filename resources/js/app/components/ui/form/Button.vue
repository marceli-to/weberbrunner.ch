<script setup>
defineProps({
	disabled: { type: Boolean, default: false },
	type: { type: String, default: 'button' },
	as: { type: [String, Object], default: 'button' },
})

const $slots = defineSlots()
</script>

<template>

	<component
		:is="as"
		class="form-button"
		:class="{ 'justify-between': $slots['icon-right'] }"
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
