@props([
  'title' => null,
  'containerClass' => null,
  'headerClass' => null,
  'mainClass' => null,
])
<x-layout.partials.head />

<x-layout.partials.body>

  <div class="w-full flex-1 md:pl-20 lg:pl-40 md:pr-0 {{ $containerClass ?? ''}}">
    
    <x-menu.page.wrapper class="bg-white px-20 py-20 w-full h-full max-h-dvh fixed left-0 top-0 z-50 md:!hidden" />
    <x-menu.buttons.hide class="w-24 h-auto fixed top-25 right-25 z-50 md:hidden" />

    <x-layout.partials.header class="flex px-20 py-20 md:hidden relative {{ $headerClass ?? '' }}">

      @if ($title)
        <h1 class="md:hidden font-semibold text-3xl">
          {{ $title }}
        </h1>
      @endif

      <x-menu.buttons.filter class="md:hidden fixed left-1/2 -translate-x-1/2 top-25 z-40" />
      
      <x-menu.buttons.show class="md:hidden w-32 h-auto fixed top-25 right-20 z-50" />

    </x-layout.partials.header>

    <x-layout.partials.main class="{{ $mainClass ?? ''}}">
      {{ $slot }}
    </x-layout.partials.main>

  </div>

</x-layout.partials.body>

<x-layout.partials.footer />
