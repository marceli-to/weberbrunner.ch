<script setup>
import { ref, watch } from 'vue'
import Cross from '@/components/icons/Cross.vue'

const props = defineProps({
	open: { type: Boolean, default: false },
	title: { type: String, default: null },
	size: { type: String, default: 'sm' },
})

const emit = defineEmits(['close'])

const dialogRef = ref(null)

const sizes = {
	sm: 'max-w-360',
	md: 'max-w-480',
	lg: 'max-w-640',
}

watch(() => props.open, (val) => {
	if (val) {
		dialogRef.value?.showModal()
	} else {
		dialogRef.value?.close()
	}
})

function onClose() {
	emit('close')
}
</script>

<template>
	<dialog
		ref="dialogRef"
		class="p-0 m-auto bg-white border border-silver shadow-xl backdrop:bg-white/50 w-full"
		:class="sizes[size]"
		@close="onClose"
		@click.self="onClose"
	>
		<div class="p-24">
			<!-- Header -->
			<div v-if="title || $slots.header" class="flex items-start justify-between mb-20">
				<slot name="header">
					<h2 class="text-sm font-semibold text-black">{{ title }}</h2>
				</slot>
				<button
					type="button"
					class="w-20 h-20 flex items-center justify-center text-black cursor-pointer -mt-2 -mr-2"
					@click="onClose"
				>
					<Cross class="w-10 h-10" />
				</button>
			</div>

			<!-- Body -->
			<div>
				<slot />
			</div>

			<!-- Footer -->
			<div v-if="$slots.footer" class="mt-24">
				<slot name="footer" />
			</div>
		</div>
	</dialog>
</template>
