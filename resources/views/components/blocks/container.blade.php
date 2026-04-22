@props(['blocks', 'standalone' => false])

@foreach($blocks->where('type', '!=', 'fixed-slider') as $block)

	@if($block->type === 'text' && $block->content)
		<x-blocks.section :title="$block->title" :standalone="$standalone" class="mb-40 lg:mb-80">
			<x-container.inner class="max-w-prose hyphens-auto">
        <article class="max-w-prose hyphens-auto">
				  {!! $block->content !!}
        </article>
			</x-container.inner>
		</x-blocks.section>

	@elseif($block->type === 'slider' && $block->media->isNotEmpty())
		<x-blocks.section :title="$block->title" :standalone="$standalone" />
		<x-slideshow.wrapper class="mb-40 lg:mb-80">
			@unless($standalone)
				<x-slot:info>
					&nbsp;
				</x-slot:info>
			@endunless
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
		<x-blocks.section :title="$block->title" :standalone="$standalone" class="mb-40 lg:mb-80">
			<x-container.inner class="max-w-prose">
				<x-media.image
					:src="$blockMedia->file"
					:alt="$blockMedia->caption ?? ''"
					:width="$blockMedia->width"
					:height="$blockMedia->height"
					class="w-full"
				/>
			</x-container.inner>
		</x-blocks.section>

	@elseif($block->type === 'links' && $block->links->where('publish', true)->isNotEmpty())
		<x-blocks.section :title="$block->title" :standalone="$standalone" class="mb-40 lg:mb-80">
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
		</x-blocks.section>

	@endif

@endforeach
