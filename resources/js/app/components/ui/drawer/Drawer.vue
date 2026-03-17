<script setup>
import { ref, watch } from 'vue'
import Cross from '@/components/icons/Cross.vue'
import Button from '@/components/ui/form/Button.vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'

const props = defineProps({
	open: { type: Boolean, default: false },
	closeable: { type: Boolean, default: true },
	submitLabel: { type: String, default: '' },
	cancelLabel: { type: String, default: '' },
	views: { type: Array, default: () => [] },
	view: { type: String, default: '' },
})

const emit = defineEmits(['close', 'submit', 'update:view'])

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
		class="p-0 m-0 ml-auto h-full max-h-full w-full max-w-full bg-transparent border-none shadow-none backdrop:bg-white/60 backdrop:select-none"
		@close="onClose"
		@click="onBackdropClick">

		<Grid :cols="12" class="h-full overflow-y-auto">

			<Span class="col-start-7 col-span-6 h-full bg-navy overflow-y-auto relative pb-20">

				<!-- Close button -->
				<button
					v-if="closeable"
					type="button"
					class="absolute top-20 left-20 w-14 h-14 flex items-center justify-center text-white cursor-pointer"
					@click="onClose">
					<Cross class="w-14 h-14" />
				</button>

				<Grid v-if="views.length" :cols="6" class="mt-140">
					<Span class="col-span-4 col-start-2 flex flex-col gap-y-10">
						<Button
							v-for="v in views"
							:key="v.value"
							:variant="view === v.value ? 'primary' : 'ghost'"
							class="px-10"
							@click="$emit('update:view', v.value)">{{ v.label }}</Button>
					</Span>
				</Grid>

				<slot />

				<Grid v-if="submitLabel || cancelLabel" :cols="6" class="mt-40">
					<Span class="col-span-4 col-start-2 flex flex-col gap-y-10">
						<Button v-if="submitLabel" variant="ghost" class="px-10" @click="$emit('submit')">{{ submitLabel }}</Button>
						<Button v-if="cancelLabel" variant="ghost" class="px-10" @click="onClose">{{ cancelLabel }}</Button>
					</Span>
				</Grid>

			</Span>
		</Grid>

	</dialog>
</template>
