<script setup>
import { ref, watch } from 'vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'

const props = defineProps({
	open: { type: Boolean, default: false },
	closeable: { type: Boolean, default: true },
	closeOnBackdrop: { type: Boolean, default: true },
	spanClass: { type: String, default: 'bg-white border-thin border-black' },
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
	if (e.target === dialogRef.value && props.closeable && props.closeOnBackdrop) {
		emit('close')
	}
}
</script>

<template>

	<dialog
		ref="dialogRef"
		class="p-0 m-auto backdrop:bg-white/60 backdrop:select-none w-full max-w-full bg-transparent border-none shadow-none"
		@close="onClose"
		@click="onBackdropClick">

		<Grid :cols="12" class="pointer-events-none">

			<Span
				class="col-start-5 col-span-6 -mx-20 p-20 pointer-events-auto"
				:class="spanClass">

				<slot />

			</Span>

		</Grid>

	</dialog>
</template>
