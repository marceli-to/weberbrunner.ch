@props([
  'title' => null,
  'location' => null,
  'backUrl' => null,
])
@php
  $referer = request()->headers->get('referer', '');
  $refererHost = parse_url($referer, PHP_URL_HOST);
  $isInternalReferrer = $refererHost === request()->getHost();
@endphp
<x-layout.partials.head />

<x-layout.partials.body>

  <x-layout.partials.header class="flex gap-x-30 md:gap-x-0 md:grid md:grid-cols-12 w-full pt-20 lg:pt-40 px-20 md:px-0 mb-20 lg:mb-40">

    <div class="md:col-span-3 md:pl-20 lg:pl-40">

      <a
        href="{{ $backUrl ?? route('page.works') }}"
        @if($isInternalReferrer) onclick="history.back(); return false;" @endif
        class="block mt-4 md:mt-0">
        <x-icons.arrow-left size="lg" class="w-20 h-auto md:w-43" />
      </a>

    </div>

    <div class="md:col-span-9 md:col-start-4">

      <x-headings.h1 class="font-semibold leading-[1.15] text-xl md:text-2xl lg:text-5xl md:-mt-5 lg:-mt-9">

        {{ $title }}

        @if ($location)
          <br>{{ $location }}
        @endif

      </x-headings.h1>

    </div>

  </x-layout.partials.header>

  <x-layout.partials.main class="pl-20 md:pl-0 pb-40 lg:pb-80">
    {{ $slot }}
  </x-layout.partials.main>

</x-layout.partials.body>

<x-layout.partials.footer />
