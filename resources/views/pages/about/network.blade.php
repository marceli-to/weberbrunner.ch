@section('meta_title', 'Netzwerk – Büro')
@section('meta_description', config('seo.page.about.network'))
<x-layout.inner title="Netzwerk">
  <x-blocks.container :blocks="$blocks" standalone />
</x-layout.inner>
