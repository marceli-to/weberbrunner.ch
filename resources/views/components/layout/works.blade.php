<x-layout.partials.head />

<x-layout.partials.body>

  <div class="lg:grid lg:grid-cols-12 w-full flex-1 px-20 lg:pl-40 lg:pr-0">
    <nav class="hidden lg:block lg:col-span-2">
      [Nav]
    </nav>
    <div class="lg:col-span-10">
      <x-layout.partials.main>
        {{ $slot }}
      </x-layout.partials.main>
    </div>
  </div>

</x-layout.partials.body>

<x-layout.partials.footer />
