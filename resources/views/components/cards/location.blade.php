@props([
  'media' => null,
  'alt' => '',
])

<x-container.inner>

  <div class="flex flex-col lg:flex-row gap-10 md:gap-20">

    @if($media)
      <div class="lg:w-1/2">
        <x-media.image
          :media="$media"
          :alt="$alt"
          sizes="(min-width: 1024px) 37vw, (min-width: 768px) 75vw, 100vw"
          :max-width="1280"
          class="w-full aspect-[16/10] object-cover" />
      </div>
    @endif

    <div class="lg:w-1/2">
      {{ $slot }}
    </div>

  </div>

</x-container.inner>
