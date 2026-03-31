@props([
  'class' => null,
  'query' => '',
  'types' => [],
  'status' => [],
  'locations' => [],
  'availableTypes' => [],
  'availableStatus' => [],
  'availableLocations' => [],
  'resultCount' => 0,
])

<div
  x-cloak
  x-show="filter"
  @click.outside="filter = false"
  x-transition:enter="transition ease-out duration-100"
  x-transition:enter-start="opacity-0"
  x-transition:enter-end="opacity-100"
  x-transition:leave="transition ease-in duration-0"
  x-transition:leave-start="opacity-100"
  x-transition:leave-end="opacity-0"
  class="{{ $class ?? ''}} flex flex-col justify-between">

  <nav class="flex flex-col justify-between gap-y-40">
    
    <div class="flex flex-col gap-y-20 md:gap-y-24">

      <ul class="flex flex-col gap-y-8">

        <x-menu.page.item
          url="{{ route('page.works') }}"
          title="Arbeiten"
          :level="1"
          :active="true" />

        <x-menu.page.item
          url="{{ route('page.about') }}"
          title="Büro"
          :level="1"
          :active="Route::is('page.about*')" />

      </ul>

      <ul class="flex flex-col gap-y-6">
        @foreach($availableTypes as $key => $label)
          <x-menu.filter.item
            :title="$label"
            :active="in_array($key, $types)"
            action="toggleType('{{ $key }}')" />
        @endforeach
      </ul>

      <ul class="flex flex-col gap-y-6">
        @foreach($availableStatus as $key => $label)
          <x-menu.filter.item
            :title="$label"
            :active="in_array($key, $status)"
            action="toggleStatus('{{ $key }}')" />
        @endforeach
      </ul>

      <ul class="flex flex-col gap-y-6 w-full">
        @foreach($availableLocations as $key => $label)
          <x-menu.filter.item
            :title="$label"
            :active="in_array($key, $locations)"
            action="toggleLocation('{{ $key }}')" />
        @endforeach
      </ul>

      {{-- Search input --}}
      <div class="flex flex-col gap-y-6 lg:pr-40 max-w-400">
        <div class="relative">
          <input
            type="text"
            wire:model.live.debounce.300ms="query"
            placeholder="Suche"
            class="w-full focus:border-b focus:border-black pb-6 md:pb-8 lg:pb-10 text-sm font-semibold text-md md:text-lg lg:text-xl text-black placeholder:text-md md:placeholder:text-lg lg:placeholder:text-xl placeholder:text-black placeholder:font-semibold focus:outline-none"
            @keydown.escape="$wire.clearSearch()" />
          @if(!empty($query))
            <button
              wire:click="clearSearch"
              class="absolute right-0 top-1/2 -translate-y-1/2 p-4 hover:opacity-60"
              title="Suche löschen">
              <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          @endif
        </div>
        @if(!empty($query))
          <div class="text-sm">
            {{ $resultCount }} {{ $resultCount === 1 ? 'Projekt' : 'Projekte' }}
          </div>
        @endif
      </div>

    </div>

    <a 
      href="{{ route('page.landing') }}" 
      title="Startseite"
      class="w-36 h-auto">
      <x-icons.logo.symbols class="w-full h-auto" />
    </a>

  </nav>

  @if(!empty($query) || !empty($types) || !empty($status) || !empty($locations))
    <button @click="filter = false" class="flex justify-end mb-20 md:hidden">
      <x-icons.arrow-right size="lg" class="w-28 h-auto" />
    </button>
  @endif

</div>
