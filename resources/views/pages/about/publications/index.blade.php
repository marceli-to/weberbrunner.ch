@section('meta_title', 'Publikationen – Büro')
@section('meta_description', config('seo.page.about.publications'))

<x-layout.inner
	title="Publikationen"
	containerClass="!pl-0"
	headerClass="pl-20 md:pl-0"
	mainClass="!pb-0 relative">

	<livewire:publications />

</x-layout.inner>
