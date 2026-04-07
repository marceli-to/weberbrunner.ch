@section('meta_title', $publication->title)
@section('meta_description', Str::limit($publication->meta_description, 160))
@if($publication->teaser->first()?->file)
	@ogImage($publication->teaser->first()->file)
@endif

<x-layout.show :title="$publication->title" :location="$publication->subtitle" :backUrl="route('page.about.publications')">

	@if (!empty($isPreview))
		<div class="bg-[#dc0000] text-white px-8 py-4 text-sm font-semibold text-center fixed top-20 right-20">Vorschau</div>
	@endif

	@if($slides->isNotEmpty())
		<x-slideshow.wrapper class="mb-20 lg:mb-40">

			<x-slot:info>
				<x-publication.info
					:items="$publicationInfo"
					:download="$download"
					:isSlideshow="true"
				/>
			</x-slot:info>

			@foreach($slides as $slide)
				<x-slideshow.slide
					:src="$slide->file"
					:width="$slide->width"
					:height="$slide->height"
					:caption="$slide->caption"
				/>
			@endforeach

		</x-slideshow.wrapper>
	@endif

	@if($publicationInfo && $slides->isEmpty())
		<div class="md:grid md:grid-cols-12 mb-40">
			<div class="md:col-span-9 md:col-start-4">
				<x-publication.info
					:items="$publicationInfo"
					:download="$download"
				/>
			</div>
		</div>
	@endif

	@foreach($publication->blocks->where('type', '!=', 'fixed-slider') as $block)

		@if($block->type === 'text' && $block->content)
			<x-work.section :title="$block->title" class="mb-40 lg:mb-80">
				<x-container.inner class="max-w-prose leading-[1.6] md:leading-[1.35]">
					{!! $block->content !!}
				</x-container.inner>
			</x-work.section>

		@elseif($block->type === 'slider' && $block->media->isNotEmpty())
			<x-work.section :title="$block->title" />
			<x-slideshow.wrapper class="mb-40 lg:mb-80">
				<x-slot:info>
					&nbsp;
				</x-slot:info>
				@foreach($block->media as $media)
					<x-slideshow.slide
						:src="$media->file"
						:width="$media->width"
						:height="$media->height"
						:caption="$media->caption"
					/>
				@endforeach
			</x-slideshow.wrapper>

		@elseif($block->type === 'image' && $block->media->first())
			@php $blockMedia = $block->media->first(); @endphp
			<x-work.section :title="$block->title" class="mb-40 lg:mb-80">
				<x-container.inner class="max-w-prose">
					<x-media.image
						:src="$blockMedia->file"
						:alt="$blockMedia->caption ?? ''"
						:width="$blockMedia->width"
						:height="$blockMedia->height"
						class="w-full"
					/>
				</x-container.inner>
			</x-work.section>

		@elseif($block->type === 'links' && $block->links->where('publish', true)->isNotEmpty())
			<x-work.section :title="$block->title" class="mb-40 lg:mb-80">
				<x-container.inner class="max-w-prose hyphens-auto">
					<div class="flex flex-col gap-y-6 md:gap-y-8 lg:gap-y-12">
						@foreach($block->links->where('publish', true) as $link)
							<x-links.cta
								:href="$link->link_type === 'internal' && $link->linkedProject ? route('page.works.show', $link->linkedProject->slug) : $link->url"
								:target="$link->link_type === 'internal' ? '_self' : '_blank'"
								:label="$link->title"
							>
								{{ $link->title }}
							</x-links.cta>
						@endforeach
					</div>
				</x-container.inner>
			</x-work.section>

		@endif

	@endforeach

</x-layout.show>
