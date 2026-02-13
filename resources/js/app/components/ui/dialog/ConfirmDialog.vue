<script setup>
import Cross from '@/components/icons/Cross.vue'
import Checkmark from '@/components/icons/Checkmark.vue'
import DialogShell from '@/components/ui/dialog/DialogShell.vue'

const props = defineProps({
	open: { type: Boolean, default: false },
	message: { type: String, default: 'Sind Sie sicher?' },
	confirmLabel: { type: String, default: 'Bestätigen' },
	cancelLabel: { type: String, default: 'Abbrechen' },
	variant: { type: String, default: 'default' },
})

const emit = defineEmits(['confirm', 'cancel'])

const variants = {
	default: {
		span: 'bg-white border border-black',
		message: 'text-black',
		cancel: 'bg-snow text-black hover:bg-silver',
		cancelIcon: 'text-black',
		confirm: 'bg-black text-white hover:bg-gray',
		confirmIcon: 'text-white',
	},
	danger: {
		span: 'bg-red border border-red',
		message: 'text-white',
		cancel: 'bg-white text-red hover:bg-snow',
		cancelIcon: 'text-red',
		confirm: 'border border-white text-white hover:bg-white/10',
		confirmIcon: 'text-white',
	},
}
</script>

<template>

	<DialogShell
		:open="open"
		:closeable="false"
		:span-class="variants[variant].span"
		@close="emit('cancel')">

		<div class="flex flex-col gap-y-20">

		<span class="text-md font-semibold" :class="variants[variant].message">
			{{ message }}
		</span>

		<div class="flex flex-col gap-8">
			<button
				type="button"
				class="flex items-center justify-between w-full px-16 py-12 text-sm font-semibold transition-colors cursor-pointer"
				:class="variants[variant].cancel"
				@click="emit('cancel')">
				<span>{{ cancelLabel }}</span>
				<Cross class="w-10 h-10" :class="variants[variant].cancelIcon" />
			</button>
			<button
				type="button"
				class="flex items-center justify-between w-full px-16 py-12 text-sm font-semibold transition-colors cursor-pointer"
				:class="variants[variant].confirm"
				@click="emit('confirm')">
				<span>{{ confirmLabel }}</span>
				<Checkmark class="w-12 h-12" :class="variants[variant].confirmIcon" />
			</button>
		</div>

		</div>

	</DialogShell>
</template>
