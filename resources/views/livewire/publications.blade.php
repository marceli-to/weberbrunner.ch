<div class="md:min-h-(--content-height-md) lg:min-h-(--content-height-lg) border-t md:border-l border-black">

	{{-- Mobile: 2 columns --}}
	<div class="flex md:hidden min-h-(--content-full-height)">
		@foreach($columns2 as $colIndex => $column)
			<div class="flex flex-col w-1/2 border-black pb-(--footer-height) {{ $colIndex === 0 ? 'border-r' : '' }}">
				@foreach($column as $publication)
					<x-cards.publication-teaser
						wire:key="publication-{{ $publication['uuid'] }}-2col"
						:title="$publication['title']"
						:media="$publication['media']"
						:slug="$publication['slug']"
						variant="sm" />
				@endforeach
			</div>
		@endforeach
	</div>

	{{-- Tablet: 3 columns --}}
	<div class="hidden md:flex lg:hidden min-h-(--content-full-height-md)">
		@foreach($columns3 as $colIndex => $column)
			<div class="flex flex-col w-1/3 border-black pb-(--footer-height-md) {{ $colIndex < 2 ? 'border-r' : '' }}">
				@foreach($column as $publication)
					<x-cards.publication-teaser
						wire:key="publication-{{ $publication['uuid'] }}-3col"
						:title="$publication['title']"
						:media="$publication['media']"
						:slug="$publication['slug']"
						variant="sm" />
				@endforeach
			</div>
		@endforeach
	</div>

	{{-- Desktop: 4 columns --}}
	<div class="hidden lg:flex">
		@foreach($columns4 as $colIndex => $column)
			<div class="flex flex-col w-1/4 border-black min-h-(--content-full-height-lg) pb-(--footer-height-lg) {{ $colIndex < 3 ? 'border-r' : '' }}">
				@foreach($column as $publication)
					<x-cards.publication-teaser
						wire:key="publication-{{ $publication['uuid'] }}-4col"
						:title="$publication['title']"
						:media="$publication['media']"
						:slug="$publication['slug']"
						variant="sm" />
				@endforeach
			</div>
		@endforeach
	</div>

</div>
