@props([
	'src',
	'width' => null,
	'height' => null,
	'caption' => null,
])

<div class="swiper-slide !w-auto flex justify-center items-center relative">
	<x-media.image
		:src="$src"
		alt=""
		:width="$width"
		:height="$height"
		class="h-(--slideshow-item-height) md:h-(--slideshow-item-height-md) xl:h-(--slideshow-item-height-xl) w-auto"
	/>
	@if($caption)
		<x-slideshow.caption>
			{{ $caption }}
		</x-slideshow.caption>
	@endif
</div>
