import { reactive } from 'vue'

const state = reactive({
	open: false,
	message: 'Sind Sie sicher?',
	confirmLabel: 'Bestätigen',
	cancelLabel: 'Abbrechen',
	variant: 'default',
	resolve: null,
})

export function useConfirm() {
	function confirm(options = {}) {
		return new Promise((resolve) => {
			Object.assign(state, {
				open: true,
				message: options.message ?? 'Sind Sie sicher?',
				confirmLabel: options.confirmLabel ?? 'Bestätigen',
				cancelLabel: options.cancelLabel ?? 'Abbrechen',
				variant: options.variant ?? 'default',
				resolve,
			})
		})
	}

	function onConfirm() {
		state.open = false
		state.resolve?.(true)
	}

	function onCancel() {
		state.open = false
		state.resolve?.(false)
	}

	return { state, confirm, onConfirm, onCancel }
}
