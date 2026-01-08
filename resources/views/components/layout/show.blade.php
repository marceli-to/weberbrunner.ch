@props([
  'title' => null,
])
<x-layout.partials.head :title="$title" />

<x-layout.partials.body>

  <x-layout.partials.header class="flex gap-x-40 lg:gap-x-0 lg:grid lg:grid-cols-12 w-full px-20 lg:px-40">
    <div class="lg:col-span-2">
      [Backlink]
    </div>
    <div class="lg:col-span-10">
      [Title]
    </div>
  </x-layout.partials.header>

  <x-layout.partials.main>
    {{ $slot }}
  </x-layout.partials.main>

</x-layout.partials.body>

<x-layout.partials.footer />
