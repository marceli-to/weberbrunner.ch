@props(['label'])

<div>
	<input type="checkbox" {{ $attributes }} />
	<label for="{{ $attributes->get('id') }}">{{ $label }}</label>
</div>
