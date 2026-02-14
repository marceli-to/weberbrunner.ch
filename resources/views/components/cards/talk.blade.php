@props([
	'title' => null,
	'link' => null,
	'link_text' => null,
])

<div class="max-w-prose text-pretty pr-20 md:pr-40">

	@if($title)
		<span class="font-semibold">
			«{{ $title }}»
		</span>
	@endif

	{{ $slot }}

	@if($link)
		<a href="{{ $link }}" class="group">
			→ <span class="underline underline-offset-4 md:underline-offset-6 decoration-1 group-hover:no-underline">{{ $link_text ?? 'Link' }}</span>
		</a>
	@endif

</div>
