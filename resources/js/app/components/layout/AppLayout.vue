<script setup>
import AppHeader from '@/components/layout/AppHeader.vue'
import AppSidebar from '@/components/layout/AppSidebar.vue'
import ToastContainer from '@/components/ui/toast/Container.vue'
import ConfirmDialog from '@/components/ui/dialog/ConfirmDialog.vue'
import GridOverlay from '@/components/ui/grid/GridOverlay.vue'
import { useConfirm } from '@/composables/useConfirm'

const { state: confirmState, onConfirm, onCancel } = useConfirm()
</script>

<template>
	<GridOverlay />
  
	<div class="min-h-screen flex flex-col">
    
		<AppHeader />

		<div class="grid grid-cols-12 gap-20 flex-1">

			<AppSidebar />

			<main class="col-span-10 col-start-3 relative pt-40">

				<ToastContainer />

				<slot />

			</main>

		</div>

		<ConfirmDialog
			:open="confirmState.open"
			:message="confirmState.message"
			:confirm-label="confirmState.confirmLabel"
			:cancel-label="confirmState.cancelLabel"
			:variant="confirmState.variant"
			@confirm="onConfirm"
			@cancel="onCancel"
		/>

	</div>
</template>
