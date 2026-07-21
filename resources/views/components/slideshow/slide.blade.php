@props([
	'media',
])
@php
	$caption = $media->caption;
@endphp

<div class="swiper-slide !w-auto flex justify-center items-center relative">
	<x-media.image
		:media="$media"
		:alt="$caption ?? ''"
		:display-heights="config('media.slideshow_heights')"
		fit="max"
		class="h-(--slideshow-item-height) md:h-(--slideshow-item-height-md) xl:h-(--slideshow-item-height-xl) w-auto"
	/>
	@if($caption)
		<x-slideshow.caption>
			{{ $caption }}
		</x-slideshow.caption>
	@endif
</div>
