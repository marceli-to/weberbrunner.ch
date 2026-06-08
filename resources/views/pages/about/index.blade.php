@section('meta_title', 'Profil – Büro')
@section('meta_description', config('seo.page.about.index'))

<x-layout.inner title="Profil">
  
  <div class="md:grid md:grid-cols-9">
    <x-icons.logo.animation class="w-full h-auto max-w-[60%] md:max-w-none md:col-span-4 mb-30 md:mb-50 lg:mb-70" />
  </div>

  <x-blocks.container :blocks="$blocks" standalone />

</x-layout.inner>
