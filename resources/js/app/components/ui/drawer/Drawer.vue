<script setup>
import { ref, watch } from 'vue'
import Cross from '@/components/icons/Cross.vue'

const props = defineProps({
	open: { type: Boolean, default: false },
	closeable: { type: Boolean, default: true },
})

const emit = defineEmits(['close'])

const dialogRef = ref(null)

watch(() => props.open, (val) => {
	if (val) {
		dialogRef.value?.showModal()
	} else {
		dialogRef.value?.close()
	}
})

function onClose() {
	if (props.closeable) {
		emit('close')
	}
}

function onBackdropClick(e) {
	if (e.target === dialogRef.value && props.closeable) {
		onClose()
	}
}
</script>

<template>
	<dialog
		ref="dialogRef"
		class="p-0 m-0 ml-auto h-full max-h-full bg-transparent border-none shadow-none backdrop:bg-white/60 backdrop:select-none"
		@close="onClose"
		@click="onBackdropClick">

		<div
			class="h-full w-[50vw] bg-navy overflow-y-auto">

			<!-- Close button -->
			<button
				v-if="closeable"
				type="button"
				class="mt-20 ml-20 w-14 h-14 flex items-center justify-center text-white cursor-pointer"
				@click="onClose">
				<Cross class="w-14 h-14" />
			</button>

			<!-- Content -->
			<div class="p-20">
				<slot />
			</div>

		</div>

	</dialog>
</template>
