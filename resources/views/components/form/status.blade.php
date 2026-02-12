@props(['type' => 'success'])

@php
$colors = [
	'success' => 'bg-lime',
	'error' => 'bg-red',
];
@endphp

<div data-form-status class="absolute left-0 top-0 w-full min-h-30 flex items-center justify-between text-white text-md font-semibold pl-10 {{ $colors[$type] ?? $colors['success'] }}">
	<span>{{ $slot }}</span>
</div>
