@props([
  'title' => null,
  'image' => null,
  'slug' => null,
  'class' => null,
  'variant' => 'default'
])
@php
  $isSmall = $variant === 'sm' ? true : false;
  $wrapperClass = $isSmall ? 'p-20 md:pb-15 lg:p-20' : 'p-20 md:p-15 md:pb-20 lg:p-20 lg:pb-30';
  $titleClass = $isSmall ? 'text-xs md:text-sm' : 'text-md md:text-lg lg:text-xl';
@endphp
<a 
  href="{{ route('page.works.show', $slug) }}" 
  aria-label="{{ $title }}"
  class="flex flex-col gap-y-15 border-b border-black {{ $wrapperClass }} {{ $class }} group">

  @if($image)
    <x-media.image :src="$image" class="group-hover:opacity-90 transition-all" />
  @endif

  <x-headings.h2 class="font-semibold leading-[1.25] group-hover:underline group-hover:underline-offset-4 group-hover:lg:underline-offset-6 group-hover:decoration-1 transition-all {{ $titleClass }}">
    {{ $title }}
  </x-headings.h2>
</a>