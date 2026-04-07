@props([
	'items' => [],
	'download' => null,
	'isSlideshow' => false,
])

<div class="text-xxs md:text-xxs lg:text-xs divide-y border-b w-full *:py-3 flex flex-col {{ $isSlideshow ? 'justify-end h-(--slideshow-item-height) md:h-(--slideshow-item-height-md) xl:h-(--slideshow-item-height-xl)' : '' }}">
	@foreach($items as $item)
		<div>
			<strong>{{ $item['label'] }}:</strong>
			{{ $item['value'] }}
		</div>
	@endforeach
	@if($download)
		<div>
			<strong>Download:</strong>
			<a
				href="{{ $download['url'] }}"
				target="_blank"
				class="no-underline hover:underline underline-offset-2">
				{{ $download['extension'] }}@if($download['size']), {{ $download['size'] }}@endif
			</a>
		</div>
	@endif
</div>
