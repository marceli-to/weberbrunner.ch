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

	<x-blocks.container :blocks="$publication->blocks" />

</x-layout.show>
