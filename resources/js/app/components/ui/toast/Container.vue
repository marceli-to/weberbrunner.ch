<script setup>
import { useToast } from '@/composables/useToast'
import ToastMessage from '@/components/ui/toast/Message.vue'

const { current, dismiss } = useToast()
</script>

<template>
	<div class="fixed top-100 left-0 w-full z-50 grid grid-cols-12 gap-20">
		<Transition
			enter-active-class="transition-opacity duration-100 ease-out"
			enter-from-class="opacity-0"
			enter-to-class="opacity-100"
			leave-active-class="transition-opacity duration-100 ease-in"
			leave-from-class="opacity-100"
			leave-to-class="opacity-0"
			mode="out-in"
		>
			<ToastMessage
				v-if="current"
				:key="current.id"
				:toast="current"
				class="col-span-9 col-start-4"
				@close="dismiss(current.id)"
			/>
		</Transition>
	</div>
</template>
