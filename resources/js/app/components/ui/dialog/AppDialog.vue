<script setup>
import Cross from '@/components/icons/Cross.vue'
import DialogShell from '@/components/ui/dialog/DialogShell.vue'

defineProps({
	open: { type: Boolean, default: false },
	title: { type: String, default: null },
})

const emit = defineEmits(['close'])
</script>

<template>

	<DialogShell :open="open" @close="emit('close')">

		<!-- Header -->
		<div
			v-if="title || $slots.header"
			class="flex items-start justify-between mb-20">

			<slot name="header">
				<h2 v-if="title" class="text-sm font-semibold text-black">{{ title }}</h2>
				<span v-else />
			</slot>

			<button
				type="button"
				class="w-20 h-20 flex items-center justify-center text-black cursor-pointer -mt-2 -mr-2 shrink-0"
				@click="emit('close')">
				<Cross class="w-10 h-10" />
			</button>

		</div>

		<!-- Body -->
		<slot />

		<!-- Footer -->
		<div v-if="$slots.footer" class="mt-20">
			<slot name="footer" />
		</div>

	</DialogShell>
</template>
