@props([
  'title' => null,
  'containerClass' => null,
  'headerClass' => null,
  'mainClass' => null,
])
<x-layout.partials.head />

<x-layout.partials.body>

  <div class="md:grid md:grid-cols-12 w-full flex-1 pl-20 md:pl-0 lg:px-0 {{ $containerClass ?? ''}}">

    <div class="md:col-span-3 ">
      <x-menu.page.wrapper class="bg-white px-20 py-20 md:pr-0 md:pl-20 md:py-0 lg:pl-40 w-full h-full max-h-dvh fixed left-0 top-0 z-30 md:!block md:sticky md:top-14 lg:mt-40 md:bg-transparent md:h-auto md:w-auto" />
      <x-menu.buttons.hide class="w-24 h-auto fixed top-25 right-25 z-50 md:hidden" />
    </div>

    <div class="md:col-span-9">

      <x-layout.partials.header class="min-h-(--header-height) md:min-h-(--header-height-md) lg:min-h-(--header-height-lg) flex flex-col md:grid md:grid-cols-9 gap-y-30 md:gap-y-0 py-20 lg:pt-40 md:px-20 lg:px-0 {{ $headerClass ?? '' }}">
        
        @if ($title)
          <h1 class="md:hidden font-semibold text-3xl">
            {{ $title }}
          </h1>
        @endif

        <x-menu.buttons.show class="md:hidden w-32 h-auto fixed top-25 right-20 z-50" />

        <a 
          href="{{ route('page.landing') }}"
          aria-label="Startseite"
          class="hidden md:block md:col-span-3 md:col-start-3 lg:col-start-4">
          <x-icons.logo.wa class="w-full h-auto xs:max-w-248 lg:max-w-400 lg:pr-40" />
        </a>

        <a 
          href="{{ route('page.landing') }}"
          aria-label="Startseite"
          class="hidden md:block md:col-span-3">
          <x-icons.logo.wpa class="w-full h-auto xs:max-w-248 lg:max-w-400 lg:pr-40" />
        </a>

      </x-layout.partials.header>

      <x-layout.partials.main class="pb-40 lg:pb-80 {{ $mainClass ?? '' }}">
        {{ $slot }}
      </x-layout.partials.main>

    </div>
  </div>

</x-layout.partials.body>

<x-layout.partials.footer />
