@props([
  'title' => null,
  'image' => null,
  'slug' => null,
  'class' => null,
  'variant' => 'default'
])
@php
  $isSmall = $variant === 'sm' ? true : false;
  $wrapperClass = $isSmall ? 'p-10 md:pb-15 lg:p-20 border-r' : 'p-20 md:pb-30';
  $titleClass = $isSmall ? 'text-xs md:text-sm' : 'text-md md:text-lg lg:text-xl';
@endphp
<a 
  href="{{ route('page.works.show', $slug) }}" 
  aria-label="{{ $title }}"
  class="flex flex-col gap-y-15 border-b border-black {{ $wrapperClass }} {{ $class }} group">

  @if($image)
    <x-media.image :src="$image" class="group-hover:opacity-90 transition-all" />
  @endif

  <x-headings.h2 class="font-semibold leading-[1.25] {{ $titleClass }}">
    {{ $title }}
  </x-headings.h2>
</a>