@props([
  'title' => null,
])
<x-layout.partials.head :title="$title" />

<x-layout.partials.body>

  <x-layout.partials.header class="px-20 lg:px-40 py-20 lg:py-40 min-h-dvh md:min-h-auto flex flex-col md:flex-row justify-between">
    <div class="flex justify-between md:items-start md:order-2">
      <div class="flex flex-col md:flex-row md:items-start gap-y-30 md:gap-y-0 md:gap-x-30">
        <x-icons.logo.wa class="w-full h-auto max-w-200 lg:max-w-280 grow-0" />
        <x-icons.logo.wpa class="w-full h-auto max-w-200 lg:max-w-280 grow-0" />
      </div>
      <div class="md:hidden">
        <x-menu.buttons.show class="w-40 h-auto" />
      </div>
    </div>
    <div class="md:order-1 flex justify-center">
      <x-icons.logo.symbol class="w-full h-auto max-w-280 md:max-w-300 lg:max-w-440" />
    </div>
  </x-layout.partials.header>

  <x-layout.partials.main>
    {{ $slot }}
  </x-layout.partials.main>

</x-layout.partials.body>

<x-layout.partials.footer />
