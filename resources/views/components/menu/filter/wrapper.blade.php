@props([
  'class' => null,
  'types' => [],
  'status' => [],
  'locations' => [],
  'publications' => false,
  'availableTypes' => [],
  'availableStatus' => [],
  'availableLocations' => [],
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
  class="{{ $class ?? ''}}">

  <nav class="bg-white flex flex-col justify-between gap-y-60">
    
    <div class="flex flex-col gap-y-20 md:gap-y-40">

      <ul class="flex flex-col gap-y-8">

        <x-menu.page.item
          url="{{ route('page.works') }}"
          title="Arbeiten"
          :level="1"
          :active="Route::is('page.works*')" />

        <x-menu.page.item
          url="{{ route('page.about') }}"
          title="Büro"
          :level="1"
          :active="Route::is('page.about*')" />

      </ul>

      <ul class="flex flex-col gap-y-2">
        @foreach($availableTypes as $key => $label)
          <x-menu.filter.item
            :title="$label"
            :active="in_array($key, $types)"
            action="toggleType('{{ $key }}')" />
        @endforeach
      </ul>

      <ul class="flex flex-col gap-y-2">
        @foreach($availableStatus as $key => $label)
          <x-menu.filter.item
            :title="$label"
            :active="in_array($key, $status)"
            action="toggleStatus('{{ $key }}')" />
        @endforeach
      </ul>

      <ul class="flex flex-col gap-y-2">
        <x-menu.filter.item
          title="Publikationen"
          :active="$publications"
          action="togglePublications" />
      </ul>

      <ul class="flex flex-col gap-y-2">
        @foreach($availableLocations as $key => $label)
          <x-menu.filter.item
            :title="$label"
            :active="in_array($key, $locations)"
            action="toggleLocation('{{ $key }}')" />
        @endforeach
      </ul>
      
    </div>

    <a 
      href="{{ route('page.landing') }}" 
      title="Startseite"
      class="w-36 h-auto">
      <x-icons.logo.symbol class="w-full h-auto" />
    </a>

  </nav>

</div>
