<div class="md:grid md:grid-cols-12 w-full">

  <div class="md:col-span-3">
    <x-menu.filter.wrapper 
      class="bg-white px-20 py-20 w-3/4 h-full max-h-dvh fixed left-0 top-0 z-30 md:!block md:sticky md:top-40 md:bg-transparent md:w-auto md:mt-20 lg:mt-40 md:px-0 md:py-0"
      :types="$types"
      :status="$status"
      :locations="$locations"
      :publications="$publications"
      :availableTypes="$availableTypes"
      :availableStatus="$availableStatus"
      :availableLocations="$availableLocations" />
  </div>

  <div class="md:min-h-(--content-full-height-md) lg:min-h-(--content-full-height-lg) md:col-span-9 md:border-l md:border-black overflow-x-hidden pb-20 md:pb-40">
    <div 
      x-data="masonry()" 
      x-init="init()" 
      @resize.window.debounce.150ms="layout()"
      class="relative"
      x-ref="container">

      @foreach($projects as $project)
        <div 
            wire:key="project-{{ $project['slug'] }}"
            class="masonry-item"
            x-ref="item">
            <x-cards.project-teaser
              :title="$project['title']"
              :image="$project['image']"
              :slug="$project['slug']"
              variant="sm" />
        </div>
      @endforeach

    </div>
  </div>
</div>
