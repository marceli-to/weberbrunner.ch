@props([
  'variant' => 'semibold',
])

<h3 {{ $attributes->merge(['class' => 'font-' . $variant]) }}>
  {{ $slot }}
</h3>