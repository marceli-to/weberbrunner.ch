@props([
	'title' => null,
	'media' => null,
	'slug' => null,
	'class' => null,
	'variant' => 'default'
])
@php
	$orientation = $media?->orientation ?? 'unknown';
	$caption = $media?->caption;
	$isSmall = $variant === 'sm' ? true : false;
	$wrapperClass = $isSmall ? 'p-10 pb-15 md:px-15 md:pb-15 lg:p-15' : 'p-20 md:p-15 md:pb-20 lg:p-20 lg:pb-25';
	$titleClass = $isSmall ? 'text-sm md:text-md' : 'text-md md:text-lg lg:text-xl';
	$imageClass = ($orientation === 'portrait' || $orientation === 'square') && $variant !== 'sm' ? 'max-w-[80%] mx-auto' : '';
@endphp
<a
	href="{{ route('page.about.publications.show', $slug) }}"
	aria-label="{{ $title }}"
	class="flex flex-col gap-y-10 border-b border-black {{ $wrapperClass }} {{ $class }} group">

	@if($media)
		<x-media.image
			:media="$media"
			:alt="$caption ?? ''"
			sizes="(min-width: 1024px) 19vw, (min-width: 768px) 25vw, 50vw"
			:max-width="1024"
			class="group-hover:opacity-90 transition-all {{ $imageClass }}"
		/>
	@endif

	<x-headings.h2 class="font-semibold leading-[1.25] group-hover:underline group-hover:underline-offset-4 group-hover:lg:underline-offset-4 group-hover:decoration-1 transition-all {{ $titleClass }}">
		{{ $title }}
	</x-headings.h2>
</a>
