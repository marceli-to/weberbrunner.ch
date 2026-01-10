@props([
  'level' => 2,
])

@php
  $tag = 'h' . $level;
@endphp

<{{ $tag }} {{ $attributes->merge(['class' => 'relative border-b border-black font-semibold pb-6 md:pb-8 lg:pb-10']) }}>
  {{ $slot }}
</{{ $tag }}>
