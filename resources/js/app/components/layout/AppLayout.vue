<script setup>
import AppHeader from '@/components/layout/AppHeader.vue'
import AppSidebar from '@/components/layout/AppSidebar.vue'
import ToastContainer from '@/components/ui/toast/Container.vue'
import ConfirmDialog from '@/components/ui/dialog/ConfirmDialog.vue'
import GridOverlay from '@/components/ui/grid/GridOverlay.vue'
import { useConfirm } from '@/composables/useConfirm'
import { usePageLoader } from '@/composables/usePageLoader'

const { state: confirmState, onConfirm, onCancel } = useConfirm()
const { state: loaderState } = usePageLoader()
</script>

<template>

  <GridOverlay />

	<div class="min-h-screen flex flex-col">

		<AppHeader />

		<div class="grid grid-cols-12 gap-20 flex-1">

			<AppSidebar />

			<main class="col-span-10 col-start-3 relative py-40 flex flex-col">

				<ToastContainer />

				<div
					class="flex-1 flex flex-col transition-opacity duration-0"
					:class="loaderState.loading ? 'opacity-0 pointer-events-none' : 'opacity-100'">
					<slot />
				</div>

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
