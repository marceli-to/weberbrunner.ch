@props([
  'title' => null,
  'location' => null
])
<x-layout.partials.head />

<x-layout.partials.body>

  <x-layout.partials.header class="flex gap-x-30 lg:gap-x-0 lg:grid lg:grid-cols-12 w-full pt-20 lg:pt-40 px-20 lg:pl-40 lg:pr-0 mb-30 lg:mb-75">
    
    <div class="lg:col-span-3">

      <a href="{{ route('page.works') }}">
        <x-icons.arrow-left size="lg" class="w-20 h-auto md:w-43" />
      </a>

    </div>

    <div class="lg:col-span-9">

      <x-headings.h1 class="font-semibold text-md md:text-2xl lg:text-5xl">

        {{ $title }}

        @if ($location)
          <br>{{ $location }}
        @endif

      </x-headings.h1>

    </div>

  </x-layout.partials.header>

  <x-layout.partials.main>
    {{ $slot }}
  </x-layout.partials.main>

</x-layout.partials.body>

<x-layout.partials.footer />
