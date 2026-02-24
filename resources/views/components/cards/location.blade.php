@props([
  'image' => null,
  'alt' => '',
])

<x-container.inner>

  <div class="flex flex-col lg:flex-row gap-10 md:gap-20">

    @if($image)
      <div class="lg:w-1/2">
        <x-media.image :src="$image" :alt="$alt" class="w-full aspect-[16/10] object-cover" />
      </div>
    @endif

    <div class="lg:w-1/2">
      {{ $slot }}
    </div>

  </div>

</x-container.inner>
