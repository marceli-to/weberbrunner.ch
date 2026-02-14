@section('meta_title', 'Jobs – Büro')
@section('meta_description', '')
<x-layout.inner title="Jobs">
	<div class="flex flex-col gap-y-24 md:gap-y-40 lg:gap-56">
		@foreach($locations as $location)
			<div>
				<x-headings.section class="mb-8 md:mb-16 lg:mb-20">
					{{ $location->title }}
				</x-headings.section>
				@foreach($location->jobs as $job)
					<x-cards.job :title="$job->title" :email="$job->contact_email">
						{!! $job->description !!}
					</x-cards.job>
				@endforeach
			</div>
		@endforeach
	</div>
</x-layout.inner>
