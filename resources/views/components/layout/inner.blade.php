@props([
  'title' => null,
])
<x-layout.partials.head />

<x-layout.partials.body>

  <div class="md:grid md:grid-cols-12 w-full flex-1 pl-20 lg:pl-40 lg:pr-0">

    <div class="md:col-span-3 ">
      <x-menu.wrapper class="bg-white px-20 py-20 w-full h-full max-h-dvh fixed left-0 top-0 z-30 md:!block md:sticky md:top-40 md:bg-transparent md:h-auto md:w-auto md:mt-20 lg:mt-40 md:px-0 md:py-0" />
      <x-menu.buttons.hide class="w-24 h-auto fixed top-25 right-25 z-50 md:hidden" />
    </div>

    <div class="md:col-span-9">

      <x-layout.partials.header class="min-h-75 md:min-h-140 lg:min-h-250 flex flex-col md:flex-row md:items-start md:justify-end gap-y-30 md:gap-y-0 md:gap-x-30 py-20 lg:pt-40 md:pr-20 lg:pr-40">

        <div class="flex justify-between">
          @if ($title)
            <h1 class="md:hidden font-semibold text-3xl">
              {{ $title }}
            </h1>
          @endif
          <x-menu.buttons.show class="md:hidden w-32 h-auto fixed top-25 right-20 z-50" />
        </div>

        <a 
          href="{{ route('page.landing') }}"
          aria-label="Startseite"
          class="hidden md:block">
          <x-icons.logo.wa class="w-full h-auto xs:max-w-248 lg:max-w-280 grow-0" />
        </a>

        <a 
          href="{{ route('page.landing') }}"
          aria-label="Startseite"
          class="hidden md:block">
          <x-icons.logo.wpa class="w-full h-auto xs:max-w-248 lg:max-w-280 grow-0" />
        </a>

      </x-layout.partials.header>

      <x-layout.partials.main class="pb-40 lg:pb-80">
        {{ $slot }}
      </x-layout.partials.main>

    </div>
  </div>

</x-layout.partials.body>

<x-layout.partials.footer />
