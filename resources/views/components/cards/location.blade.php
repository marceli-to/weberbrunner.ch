@props([
  'image' => null,
  'alt' => '',
  'mapsUrl' => null,
])

<x-container.inner>
  <div class="flex flex-col lg:flex-row gap-10 md:gap-20">

    <div class="lg:w-1/2">
      <x-media.image :src="$image" :alt="$alt" class="w-full aspect-[16/10] object-cover" />
    </div>

    <div class="lg:w-1/2">
      {{ $slot }}

      @if($mapsUrl)
        <div class="mt-6">
          <x-links.cta href="{{ $mapsUrl }}">
            Google Maps
          </x-links.cta>
        </div>
      @endif
    </div>
    
  </div>
</x-container.inner>
