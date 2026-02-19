<script setup>
import { ref, watch } from 'vue'
import Cross from '@/components/icons/Cross.vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'

const props = defineProps({
	open: { type: Boolean, default: false },
	title: { type: String, default: null },
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
		class="p-0 m-auto backdrop:bg-white/60 backdrop:select-none w-full max-w-full bg-transparent border-none shadow-none"
		@close="onClose"
		@click="onBackdropClick">

		<Grid :cols="12">

			<Span class="col-start-4 col-span-8 -mx-20 bg-white border-thin border-black py-20">

				<!-- Header -->
				<div
					v-if="title || $slots.header || closeable"
					class="flex items-start justify-between mb-20 px-20">

					<slot name="header">
						<h2 v-if="title" class="text-sm font-semibold text-black">{{ title }}</h2>
						<span v-else />
					</slot>

					<button
						v-if="closeable"
						type="button"
						class="w-20 h-20 flex items-center justify-center text-black cursor-pointer -mt-2 -mr-2 shrink-0"
						@click="onClose">
						<Cross class="w-10 h-10" />
					</button>

				</div>

				<!-- Body -->
				<slot />

				<!-- Footer -->
				<div v-if="$slots.footer" class="mt-20 px-20">
					<slot name="footer" />
				</div>

			</Span>

		</Grid>

	</dialog>
</template>
