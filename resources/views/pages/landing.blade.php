@section('meta_description', 'Weberbrunner Architektur – Architektur und Planung')
<x-layout.landing :text="$text">

  {{-- Mobile: interleaved row-first order --}}
  <section class="border-t border-black pb-20 md:hidden">
    @foreach($column as $project)
      <x-cards.project-teaser
        :title="$project['title']"
        :image="$project['image']"
        :width="$project['width']"
        :height="$project['height']"
        :orientation="$project['orientation']"
        :slug="$project['slug']"
        :caption="$project['caption']"
        :class="$loop->last ? '!border-b-0' : ''" />
    @endforeach
  </section>

  {{-- Desktop: 3-column grid --}}
  <section class="border-t border-black hidden md:divide-x md:divide-black md:grid md:grid-cols-12">
    @foreach($columns as $colIndex => $column)
      <div class="md:col-span-4 pb-20 md:pb-30 lg:pb-40">
        @foreach($column as $projectIndex => $project)
          <x-cards.project-teaser
            :title="$project['title']"
            :image="$project['image']"
            :width="$project['width']"
            :height="$project['height']"
            :orientation="$project['orientation']"
            :slug="$project['slug']"
            :caption="$project['caption']"
            :class="$colIndex === 2 && $loop->last ? '!border-b-0 md:!border-b' : ''" />
        @endforeach
      </div>
    @endforeach
  </section>

</x-layout.landing>
