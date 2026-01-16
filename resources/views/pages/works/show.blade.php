@section('meta_title', 'Wohnüberbauung Hagmannareal, Winterthur')
@section('meta_description', '')
@php
$slides = [
  ['src' => 'images/dummy-project-1.jpg'],
  ['src' => 'images/dummy-project-2.jpg'],
  ['src' => 'images/dummy-project-3.jpg'],
  ['src' => 'images/dummy-project-4.jpg'],
  ['src' => 'images/dummy-project-5.jpg'],
];
@endphp

<x-layout.show title="Wohnüberbauung Hagmannareal" location="Winterthur">

  <x-media.slideshow class="mb-20 lg:mb-40">

    <x-slot:info>
      <div class="flex flex-col justify-end h-(--slideshow-item-height) md:h-(--slideshow-item-height-md) lg:h-(--slideshow-item-height-lg)">
        <div>Projekt: Muster</div>
        <div>Umsetzung: 2025</div>
        <div>Budget: 2.5 Mio.</div>
        <div>Auszeichnungen: Architekturpreise 2025, Gute Bauten 2025 (1. Platz)</div>
      </div>
    </x-slot:info>

    @foreach($slides as $slide)
      <div class="swiper-slide !w-auto flex justify-center items-center">
        <x-media.image
          :src="$slide['src']"
          alt=""
          class="h-(--slideshow-item-height) md:h-(--slideshow-item-height-md) lg:h-(--slideshow-item-height-lg) w-auto"
        />
      </div>
    @endforeach

  </x-media.slideshow>

  <div class="md:grid md:grid-cols-12 pl-20 lg:pl-40 mb-40 lg:mb-80">

    <div class="md:col-span-9 md:col-start-4">

      <x-headings.section class="mb-6 md:mb-8 lg:mb-10">
        Projektbeschrieb
      </x-headings.section>

      <x-container.inner class="max-w-prose hyphens-auto">
        <p> lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. </p>
      </x-container.inner> 

    </div>

  </div>

  <div class="md:grid md:grid-cols-12 pl-20 lg:pl-40">

    <div class="md:col-span-9 md:col-start-4">
      <x-headings.section class="mb-6 md:mb-8 lg:mb-10">
        Grundrisse
      </x-headings.section>

    </div>

  </div>

  <x-media.slideshow class="mb-20 lg:mb-40">

    <x-slot:info>
      &nbsp;
    </x-slot:info>

    @foreach($slides as $slide)
      <div class="swiper-slide !w-auto flex justify-center items-center">
        <x-media.image
          :src="$slide['src']"
          alt=""
          class="h-(--slideshow-item-height) md:h-(--slideshow-item-height-md) lg:h-(--slideshow-item-height-lg) w-auto"
        />
      </div>
    @endforeach

  </x-media.slideshow>


</x-layout.show>
