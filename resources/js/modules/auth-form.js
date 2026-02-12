const form = document.querySelector('[data-auth-form]');
if (form) {
	let status = document.querySelector('[data-form-status]');

	// Clear error state and status message on focus
	for (const input of form.querySelectorAll('input')) {
		input.addEventListener('focus', () => {
			input.classList.remove('has-error');

			// Reset eye icon color for password fields
			const toggle = input.parentElement.querySelector('[data-pw-toggle]');
			toggle?.classList.remove('text-white');

			// Remove status message
			if (status) {
				status.remove();
				status = null;
			}
		});
	}

	// Password visibility toggle
	for (const button of form.querySelectorAll('[data-pw-toggle]')) {
		button.addEventListener('click', () => {
			const input = button.parentElement.querySelector('input');
			const isPassword = input.type === 'password';
			input.type = isPassword ? 'text' : 'password';

			const iconOff = button.querySelector('[data-pw-icon="off"]');
			const iconOn = button.querySelector('[data-pw-icon="on"]');
			iconOff.style.display = isPassword ? 'none' : '';
			iconOn.style.display = isPassword ? '' : 'none';
		});
	}
}
