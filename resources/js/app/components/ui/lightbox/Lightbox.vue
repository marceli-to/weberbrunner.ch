<script setup>
import { ref, watch, onMounted, onBeforeUnmount } from 'vue'
import Cross from '@/components/icons/Cross.vue'

const props = defineProps({
	open: { type: Boolean, default: false },
	title: { type: String, default: null },
	size: { type: String, default: 'md' },
	closeable: { type: Boolean, default: true },
	padded: { type: Boolean, default: true },
})

const emit = defineEmits(['close'])

const dialogRef = ref(null)

const sizes = {
	sm: 'max-w-480',
	md: 'max-w-640',
	lg: 'max-w-800',
	xl: 'max-w-960',
	full: 'max-w-[calc(100vw-80px)]',
}

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
	if (e.target === dialogRef.value) {
		onClose()
	}
}
</script>

<template>
	<dialog
		ref="dialogRef"
		class="p-0 m-auto bg-white border border-silver shadow-xl backdrop:bg-white/50 w-full"
		:class="sizes[size]"
		@close="onClose"
		@click="onBackdropClick"
	>
		<div :class="padded ? 'p-24' : ''">
			<!-- Header -->
			<div
				v-if="title || $slots.header || closeable"
				class="flex items-start justify-between"
				:class="[
					padded ? 'mb-20' : 'px-24 pt-24 pb-20',
				]"
			>
				<slot name="header">
					<h2 v-if="title" class="text-sm font-semibold text-black">{{ title }}</h2>
					<span v-else />
				</slot>
				<button
					v-if="closeable"
					type="button"
					class="w-20 h-20 flex items-center justify-center text-black cursor-pointer -mt-2 -mr-2 shrink-0"
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
			<div
				v-if="$slots.footer"
				:class="padded ? 'mt-24' : 'px-24 pb-24 pt-20'"
			>
				<slot name="footer" />
			</div>
		</div>
	</dialog>
</template>
