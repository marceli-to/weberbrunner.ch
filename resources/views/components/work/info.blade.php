@props([
  'items' => [],
  'header' => null,
  'city' => null,
  'isSlideshow' => false,
])

<div class="text-xxs md:text-xxs lg:text-xs divide-y border-b w-full *:py-3 flex flex-col {{ $isSlideshow ? 'justify-end h-(--slideshow-item-height) md:h-(--slideshow-item-height-md) xl:h-(--slideshow-item-height-xl)' : '' }}">
  @if($header)
    <div>
      <strong>{{ $header }}</strong>
    </div>
  @endif
  @if($city)
    <div>
      <strong>Ort:</strong> {{ $city }}
    </div>
  @endif
  @foreach($items as $item)
    <div>
      <strong>{{ $item['label'] }}:</strong> {{ $item['value'] }}
    </div>
  @endforeach
  </div>
