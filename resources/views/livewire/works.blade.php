<div class="md:grid md:grid-cols-12 w-full">

  <x-menu.buttons.filter 
    wire:key="filter-btn-{{ $hasActiveFilters ? 'active' : 'inactive' }}"
    class="md:hidden fixed left-1/2 -translate-x-1/2 top-25 z-40" 
    :hasActiveFilters="$hasActiveFilters" />

  <div class="md:col-span-3 lg:col-span-4">
    <x-menu.filter.wrapper 
      class="bg-white px-20 py-20 md:pr-0 md:pl-20 lg:pl-40 w-full h-full max-h-dvh fixed left-0 top-0 z-50 md:!block md:sticky md:top-14 lg:top-40 md:h-auto md:bg-transparent md:w-auto md:py-0"
      :types="$types"
      :status="$status"
      :locations="$locations"
      :publications="$publications"
      :availableTypes="$availableTypes"
      :availableStatus="$availableStatus"
      :availableLocations="$availableLocations" />
  </div>

  <div class="md:min-h-(--content-full-height-md) lg:min-h-(--content-full-height-lg) md:col-span-9 lg:col-span-8 md:border-l md:border-black overflow-x-hidden">
    
    {{-- Mobile: 2 columns --}}
    <div class="flex md:hidden min-h-(--content-full-height) border-t border-black">
      @foreach($columns2 as $colIndex => $column)
        <div class="flex flex-col w-1/2 border-black pb-(--footer-height) {{ $colIndex === 0 ? 'border-r' : '' }}">
          @foreach($column as $project)
            <x-cards.project-teaser
              wire:key="project-{{ $project['slug'] }}-2col"
              :title="$project['title']"
              :image="$project['image']"
              :slug="$project['slug']"
              variant="sm" />
          @endforeach
        </div>
      @endforeach
    </div>

    {{-- Tablet: 3 columns --}}
    <div class="hidden md:flex lg:hidden min-h-(--content-full-height-md)">
      @foreach($columns3 as $colIndex => $column)
        <div class="flex flex-col w-1/3 border-black pb-(--footer-height-md) {{ $colIndex < 2 ? 'border-r' : '' }}">
          @foreach($column as $project)
            <x-cards.project-teaser
              wire:key="project-{{ $project['slug'] }}-3col"
              :title="$project['title']"
              :image="$project['image']"
              :slug="$project['slug']"
              variant="sm" />
          @endforeach
        </div>
      @endforeach
    </div>

    {{-- Desktop: 4 columns --}}
    <div class="hidden lg:flex">
      @foreach($columns4 as $colIndex => $column)
        <div class="flex flex-col w-1/4 border-black min-h-(--content-full-height-lg) pb-(--footer-height-lg) {{ $colIndex < 3 ? 'border-r' : '' }}">
          @foreach($column as $project)
            <x-cards.project-teaser
              wire:key="project-{{ $project['slug'] }}-4col"
              :title="$project['title']"
              :image="$project['image']"
              :slug="$project['slug']"
              variant="sm" />
          @endforeach
        </div>
      @endforeach
    </div>

  </div>
</div>
