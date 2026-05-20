@section('meta_title', 'Arbeitsweisen – Büro')
@section('meta_description', config('seo.page.about'))

<x-layout.inner title="Arbeitsweisen">

  <x-blocks.container :blocks="$blocks" standalone />

</x-layout.inner>
