@props([
  'level' => 2,
])

@php
  $tag = 'h' . $level;
@endphp

<div >

  <{{ $tag }} {{ $attributes->merge(['class' => 'relative border-b border-black']) }}>
    {{ $slot }}
  </{{ $tag }}>

</div>
