<script setup>
import { ref, watch } from 'vue'
import Cross from '@/components/icons/Cross.vue'
import Checkmark from '@/components/icons/Checkmark.vue'

const props = defineProps({
	open: { type: Boolean, default: false },
	message: { type: String, default: 'Sind Sie sicher?' },
	confirmLabel: { type: String, default: 'Bestätigen' },
	cancelLabel: { type: String, default: 'Abbrechen' },
	variant: { type: String, default: 'default' },
})

const emit = defineEmits(['confirm', 'cancel'])

const dialogRef = ref(null)

const variants = {
	default: {
		dialog: 'bg-white border border-silver',
		message: 'text-black',
		cancel: 'bg-snow text-black hover:bg-silver',
		cancelIcon: 'text-black',
		confirm: 'bg-black text-white hover:bg-gray',
		confirmIcon: 'text-white',
	},
	danger: {
		dialog: 'bg-red',
		message: 'text-white',
		cancel: 'bg-white text-red hover:bg-snow',
		cancelIcon: 'text-red',
		confirm: 'border border-white text-white hover:bg-white/10',
		confirmIcon: 'text-white',
	},
}

watch(() => props.open, (val) => {
	if (val) {
		dialogRef.value?.showModal()
	} else {
		dialogRef.value?.close()
	}
})

function onClose() {
	emit('cancel')
}
</script>

<template>
	<dialog
		ref="dialogRef"
		class="p-0 m-auto shadow-xl backdrop:bg-white/50 w-full max-w-480"
		:class="variants[variant].dialog"
		@close="onClose"
		@click.self="onClose"
	>
		<div class="p-24">
			<p class="mb-24" :class="variants[variant].message">
				{{ message }}
			</p>

			<div class="flex flex-col gap-8">
				<button
					type="button"
					class="flex items-center justify-between w-full px-16 py-12 text-sm font-semibold transition-colors cursor-pointer"
					:class="variants[variant].cancel"
					@click="emit('cancel')"
				>
					<span>{{ cancelLabel }}</span>
					<Cross class="w-10 h-10" :class="variants[variant].cancelIcon" />
				</button>
				<button
					type="button"
					class="flex items-center justify-between w-full px-16 py-12 text-sm font-semibold transition-colors cursor-pointer"
					:class="variants[variant].confirm"
					@click="emit('confirm')"
				>
					<span>{{ confirmLabel }}</span>
					<Checkmark class="w-12 h-12" :class="variants[variant].confirmIcon" />
				</button>
			</div>
		</div>
	</dialog>
</template>
