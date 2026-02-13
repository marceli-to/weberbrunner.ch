@props(['error' => null])

<div class="relative">

	<input {{ $attributes->merge(['type' => 'password']) }} class="form-input form-input--sm {{ $error ? 'has-error' : '' }}" />

	<button type="button" data-pw-toggle class="absolute right-0 top-1/2 -translate-y-1/2 right-10 {{ $error ? 'text-white' : '' }}">

		<span data-pw-icon="off" class="w-16 h-16 flex items-center justify-center">
			<x-icons.auth.eye-off class="w-16 h-auto" />
		</span>

		<span data-pw-icon="on" class="w-16 h-16 flex items-center justify-center" style="display:none;">
			<x-icons.auth.eye-on class="w-16 h-auto" />
		</span>

	</button>

</div>
