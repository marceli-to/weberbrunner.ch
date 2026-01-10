@props([
  'weight' => 'semibold',
])

<h3 {{ $attributes->merge(['class' => 'font-' . $weight]) }}>
  {{ $slot }}
</h3>