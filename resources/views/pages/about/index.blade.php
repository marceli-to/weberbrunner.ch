@section('meta_title', 'Profil – Büro')
@section('meta_description', 'Weberbrunner Architektur – Architektur und Planung')

<x-layout.inner title="Profil">
  
  <div class="md:grid md:grid-cols-9">
    <x-icons.logo.animation class="w-full h-auto max-w-[60%] md:max-w-none md:col-span-4 mb-30 md:mb-50 lg:mb-70" />
  </div>
  
  @if ($title)
    <x-headings.section class="mb-8 md:mb-16 lg:mb-20">
      {{ $title }}
    </x-headings.section>
  @endif

  @if ($text)
    <x-container.inner>
      <p class="max-w-prose">
        {{ $text }}
      </p>
    </x-container.inner>
  @endif

</x-layout.inner>
