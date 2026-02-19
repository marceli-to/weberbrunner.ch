<script setup>
defineProps({
	active: { type: Boolean, default: false },
})

const $slots = defineSlots()
</script>

<template>
	<button
		type="button"
		class="form-button col-span-2 px-20"
		:class="active ? 'form-button--active' : 'form-button--muted'">
		<span v-if="$slots.icon" class="shrink-0">
			<slot name="icon" />
		</span>
		<span>
			<slot />
		</span>
	</button>
</template>
