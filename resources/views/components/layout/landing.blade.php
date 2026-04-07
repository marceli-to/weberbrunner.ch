<x-layout.partials.head />

<x-layout.partials.body>

  <x-layout.partials.header class="px-20 md:px-0">
    
    <div class="flex flex-col md:flex-row justify-between md:items-start gap-y-60 md:gap-y-0 py-20 md:pb-0 lg:pt-40 md:min-h-auto md:grid md:grid-cols-12 w-full">
      <div class="md:order-2 md:col-span-7 md:col-start-6 lg:col-start-7 flex justify-between -mt-6 lg:-mt-8 xl:-mt-10">
        <div class="gap-y-30 grid grid-cols-6 w-full md:w-auto lg:w-full">
          <x-icons.logo.wa class="w-full h-auto xs:max-w-248 lg:max-w-400 col-span-4 md:col-span-3 md:pr-20 lg:pr-40" />
          <x-icons.logo.wpa class="w-full h-auto xs:max-w-248 lg:max-w-400 col-span-4 md:col-span-3 md:col-start-4 md:pr-20 lg:pr-40" />
    
          @if($text)
            <div class="hidden md:block col-span-6 xl:col-span-5 pr-20 lg:pr-40 xl:pr-0 mt-60 xl:mt-80 2xl:mt-120 font-semibold text-2xl lg:text-3xl xl:text-4xl">
              {{ $text }}
            </div>
          @endif

        </div>
        <x-menu.buttons.show class="md:hidden w-32 h-auto fixed top-21 right-20 z-50" />
      </div>
      <div class="md:order-1 md:col-span-4 md:pl-20 lg:pl-40 grid grid-cols-6 md:block">
        <x-icons.logo.animation class="w-full h-auto col-span-4 col-start-2" />
      </div>

      @if($text)
        <div class="md:hidden font-semibold text-xl">
          {{ $text }}
        </div>
      @endif

    </div>

    <x-menu.page.wrapper class="bg-white px-20 py-20 md:pr-0 md:pl-20 lg:pl-40 md:pb-10 md:pt-0 lg:py-0 w-full h-full max-h-dvh fixed left-0 top-0 z-30 md:!block md:relative md:bg-transparent md:h-auto md:w-auto md:mt-40 lg:mt-80" />
    <x-menu.buttons.hide class="w-24 h-auto fixed top-21 right-25 z-50 md:hidden" />

  </x-layout.partials.header>

  <x-layout.partials.main class="mt-20 lg:mt-40">
    {{ $slot }}
  </x-layout.partials.main>

</x-layout.partials.body>

<x-layout.partials.footer />
