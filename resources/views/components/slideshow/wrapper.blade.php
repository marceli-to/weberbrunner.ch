@props([
	'info' => null,
])

<div {{ $attributes->merge(['class' => 'swiper']) }} data-slideshow x-data="{ captionOpen: true }">

	<div class="swiper-wrapper relative">

		@if($info)
			<div class="swiper-slide w-col-3-offset pl-40 pr-10 justify-center items-center bg-white !hidden lg:!flex">
				{{ $info }}
			</div>
		@endif

		{{ $slot }}

	</div>

	<button class="swiper-btn-prev cursor-pointer absolute left-0 top-0 z-50 w-20 lg:w-120 xl:w-200 h-(--slideshow-item-height) md:h-(--slideshow-item-height-md) xl:h-(--slideshow-item-height-xl) transition-opacity duration-300" style="opacity: 0; pointer-events: none;">
		<x-icons.chevron-left class="absolute top-1/2 -translate-y-1/2 left-12 xl:left-40 w-16 md:w-20 xl:w-24" />
	</button>

	<button class="swiper-btn-next cursor-pointer absolute right-0 top-0 z-50 w-20 lg:w-120 xl:w-200 h-(--slideshow-item-height) md:h-(--slideshow-item-height-md) xl:h-(--slideshow-item-height-xl)">
		<x-icons.chevron-right class="absolute top-1/2 -translate-y-1/2 right-12 xl:right-40 w-16 md:w-20 xl:w-24" />
	</button>

</div>
