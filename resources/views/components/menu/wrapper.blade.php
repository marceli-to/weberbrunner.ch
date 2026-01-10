@props([
  'class' => null,
])
<div
  x-cloak
  x-show="menu"
  @click.outside="menu = false"
  x-transition:enter="transition ease-out duration-100"
  x-transition:enter-start="opacity-0"
  x-transition:enter-end="opacity-100"
  x-transition:leave="transition ease-in duration-0"
  x-transition:leave-start="opacity-100"
  x-transition:leave-end="opacity-0"
  class="{{ $class ?? ''}}">

  <nav class="h-[inherit] flex flex-col justify-between">

    <div class="flex flex-col gap-y-20 md:gap-y-24">

      <ul class="flex flex-col gap-y-24 md:gap-y-16">

        <x-menu.item
          url="{{ route('page.works') }}"
          title="Arbeiten"
          :level="1"
          :active="Route::is('page.works*')" />

        <x-menu.item
          url="{{ route('page.about') }}"
          title="Büro"
          :level="1"
          :active="Route::is('page.about*')" />
      </ul>

      @if (!Route::is('page.landing'))

        <ul class="flex flex-col gap-y-2">
          <x-menu.item
            url="{{ route('page.about.team') }}"
            title="Team"
            :level="2"
            :active="Route::is('page.about.team')" />

          <x-menu.item
            url="{{ route('page.about.jobs') }}"
            title="Jobs"
            :level="2"
            :active="Route::is('page.about.jobs')" />

          <x-menu.item
            url="{{ route('page.about.contact') }}"
            title="Kontakt"
            :level="2"
            :active="Route::is('page.about.contact')" />
        </ul>

        <ul class="flex flex-col gap-y-2">
          <x-menu.item
            url="{{ route('page.about.network') }}"
            title="Netzwerk"
            :level="2"
            :active="Route::is('page.about.network')" />
        </ul>

        <ul class="flex flex-col gap-y-2">
          <x-menu.item
            url="{{ route('page.about.talks') }}"
            title="Vorträge"
            :level="2"
            :active="Route::is('page.about.talks')" />

          <x-menu.item
            url="{{ route('page.about.jury') }}"
            title="Jury"
            :level="2"
            :active="Route::is('page.about.jury')" />

          <x-menu.item
            url="{{ route('page.about.awards') }}"
            title="Auszeichnungen"
            :level="2"
            :active="Route::is('page.about.awards')" />
        </ul>

        <ul>
          <x-menu.item
            url="{{ route('page.search') }}"
            title="Suche"
            :level="2"
            :active="Route::is('page.search*')" />
        </ul>
      @endif
      
    </div>

    <a 
      href="{{ route('page.landing') }}" 
      title="Startseite"
      class="w-36 h-auto md:hidden">
      <x-icons.logo.symbol class="w-full h-auto" />
    </a>

  </nav>
</div>
