@props([
	'items' => [],
	'isSlideshow' => false,
])

<div class="text-xxs md:text-xxs lg:text-xs divide-y border-b w-full *:py-3 flex flex-col {{ $isSlideshow ? 'justify-end h-(--slideshow-item-height) md:h-(--slideshow-item-height-md) xl:h-(--slideshow-item-height-xl)' : '' }}">
	@foreach($items as $item)
		<div>
			<strong>{{ $item['label'] }}:</strong>
			@if(!empty($item['link']))
				<a 
          href="{{ $item['link'] }}" 
          target="_blank" 
          class="no-underline hover:underline underline-offset-2"
          aria-label="Download {{ $item['value'] }}">
					{{ $item['value'] }}
				</a>
			@else
				{{ $item['value'] }}
			@endif
		</div>
	@endforeach
</div>
