<script setup>
import Cross from '@/components/icons/Cross.vue'
import Checkmark from '@/components/icons/Checkmark.vue'
import Button from '@/components/ui/form/Button.vue'
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
		span: 'bg-navy border-thin border-navy',
		message: 'text-white',
		cancel: 'secondary',
		confirm: 'primary',
	},
	danger: {
		span: 'bg-red border-thin border-red',
		message: 'text-white',
		cancel: 'danger-outline',
		confirm: 'danger',
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
				<Button
					class="px-20"
					:variant="variants[variant].cancel"
					@click="emit('cancel')">
					{{ cancelLabel }}
					<template #icon-right>
						<Cross class="w-10 h-10" />
					</template>
				</Button>
				<Button
					class="px-20"
					:variant="variants[variant].confirm"
					@click="emit('confirm')">
					{{ confirmLabel }}
					<template #icon-right>
						<Checkmark class="w-12 h-12" />
					</template>
				</Button>
			</div>

		</div>

	</DialogShell>
</template>
