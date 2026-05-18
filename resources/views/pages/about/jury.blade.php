@section('meta_title', 'Jury – Büro')
@section('meta_description', config('seo.page.about.jury'))
<x-layout.inner title="Jury">
	<x-container.inner class="!pr-0 flex flex-col gap-y-16 md:gap-y-24 lg:gap-y-48">
		@foreach($sections as $section)
			<div>
				<x-headings.section class="mb-8 md:mb-16 lg:mb-20">
					{{ $section->title }}
				</x-headings.section>
				<div class="flex flex-col gap-y-8 md:gap-y-16">
					@foreach($section->juries as $jury)
						<x-cards.jury>{!! $jury->text !!}</x-cards.jury>
					@endforeach
				</div>
			</div>
		@endforeach
	</x-container.inner>
</x-layout.inner>
