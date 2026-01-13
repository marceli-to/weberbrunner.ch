<x-layout.partials.head />

<x-layout.partials.body>

  <x-layout.partials.header class="px-20 lg:pl-40 lg:pr-0">
    
    <div class="flex flex-col md:flex-row justify-between md:items-start py-20 md:pb-0 lg:pt-40 lg:pr-40 min-h-dvh md:min-h-auto w-full">
      <div class="md:order-2 flex justify-between">
        <div class="flex flex-col md:flex-row md:items-start gap-y-30 md:gap-y-0 md:gap-x-30">
          <x-icons.logo.wa class="w-full h-auto max-w-200 xs:max-w-248 lg:max-w-280" />
          <x-icons.logo.wpa class="w-full h-auto max-w-200 xs:max-w-248 lg:max-w-280" />
        </div>
        <x-menu.buttons.show class="md:hidden w-32 h-auto fixed top-25 right-20 z-50" />
      </div>
      <div class="md:order-1 flex justify-center">
        <x-icons.logo.symbol class="w-full h-auto max-w-280 lg:max-w-300 xl:max-w-440" />
      </div>
    </div>

    <x-menu.page.wrapper class="bg-white px-20 py-20 w-full h-full max-h-dvh fixed left-0 top-0 z-30 md:!block md:relative md:bg-transparent md:h-auto md:w-auto md:mt-60 lg:mt-80 md:px-0 md:py-0" />
    <x-menu.buttons.hide class="w-24 h-auto fixed top-25 right-25 z-50 md:hidden" />
    
  </x-layout.partials.header>

  <x-layout.partials.main class="mt-20 lg:mt-40">
    {{ $slot }}
  </x-layout.partials.main>

</x-layout.partials.body>

<x-layout.partials.footer />
