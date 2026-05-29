@props(['member'])

<div class="flex flex-col p-20 pb-25">

	@if($member->image)
		<x-media.image
			:media="$member->image"
			:alt="$member->firstname . ' ' . $member->name"
			sizes="(min-width: 1024px) 25vw, (min-width: 768px) 35vw, 70vw"
			:max-width="1024"
			class="w-full aspect-3/4 object-cover max-w-[70%] mx-auto mb-20"
		/>
	@else
		<div class="w-full aspect-3/4 bg-[#f9f9f9] max-w-[70%] mx-auto mb-20"></div>
	@endif

	<div class="font-semibold text-xs md:text-xxs lg:text-sm flex flex-col">
		<x-headings.h2>
			@if(count($member->bios) && $member->slug)
				<a href="{{ route('page.about.team.show', $member->slug) }}" class="underline underline-offset-4 decoration-1 hover:no-underline">
					{{ $member->firstname }} {{ $member->name }}
				</a>
			@else
				{{ $member->firstname }} {{ $member->name }}
			@endif
		</x-headings.h2>
		@if($member->title)
			<span>{{ $member->title }}</span>
		@endif
		@if($member->since)
			<span>Mitarbeit seit {{ $member->since }}</span>
		@endif
		@if($member->email)
			<a
				href="mailto:{{ $member->email }}"
				class="underline underline-offset-4 decoration-1 hover:no-underline">
				E-Mail
			</a>
		@endif
	</div>

</div>
