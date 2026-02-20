@props([
  'title' => null,
  'image' => null,
  'width' => null,
  'height' => null,
  'orientation' => null,
  'slug' => null,
  'class' => null,
  'variant' => 'default'
])
@php
  $isSmall = $variant === 'sm' ? true : false;
  $wrapperClass = $isSmall ? 'p-10 pb-15 md:px-15 md:pb-15 lg:p-15' : 'p-20 md:p-15 md:pb-20 lg:p-20 lg:pb-25';
  $titleClass = $isSmall ? 'text-sm md:text-md' : 'text-md md:text-lg lg:text-xl';
  $imageClass = ($orientation === 'portrait' || $orientation === 'square') && $variant !== 'sm' ? 'max-w-[80%] mx-auto' : '';
@endphp
<a 
  href="{{ route('page.works.show', $slug) }}" 
  aria-label="{{ $title }}"
  class="flex flex-col gap-y-10 border-b border-black {{ $wrapperClass }} {{ $class }} group">

  @if($image)
    <x-media.image
      :src="$image"
      :width="$width"
      :height="$height"
      class="group-hover:opacity-90 transition-all {{ $imageClass }}"
    />
  @endif

  <x-headings.h2 class="font-semibold leading-[1.25] group-hover:underline group-hover:underline-offset-4 group-hover:lg:underline-offset-4 group-hover:decoration-1 transition-all {{ $titleClass }}">
    {{ $title }}
  </x-headings.h2>
</a>