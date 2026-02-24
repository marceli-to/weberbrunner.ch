@php
  // Prepare slides from media (excluding teaser)
  $slides = $project->media->where('is_teaser', false)->map(fn($m) => [
    'src' => $m->file,
    'width' => $m->width,
    'height' => $m->height,
    'caption' => $m->caption,
  ])->values();

  // Prepare project info from attributes
  $projectInfo = $project->attributes->map(fn($attr) => [
    'label' => $attr->label,
    'value' => $attr->value,
  ])->toArray();

  // Get first category as header
  $header = $project->categories->first()?->title ?? 'weberbrunner architekten';
@endphp

@section('meta_title', $project->full_title)
@section('meta_description', Str::limit($project->description, 160))
@if($project->teaser->first()?->file)
	@ogImage($project->teaser->first()->file)
@endif


<x-layout.show :title="$project->title" :location="$project->city">
  @if ($isPreview)
    <div class="bg-[#dc0000] text-white px-8 py-4 text-sm font-semibold text-center fixed top-20 right-20">Vorschau</div>
  @endif

  @if($slides->isNotEmpty())
    <x-slideshow.wrapper class="mb-20 lg:mb-40">

      <x-slot:info>
        <x-work.info
          :items="$projectInfo"
          :header="$header"
          :isSlideshow="true"
        />
      </x-slot:info>

      @foreach($slides as $slide)
        <x-slideshow.slide
          :src="$slide['src']"
          :width="$slide['width']"
          :height="$slide['height']"
          :caption="$slide['caption']"
        />
      @endforeach

    </x-slideshow.wrapper>
  @endif

  @if($project->description)
    <x-work.description>
      <p>{{ $project->description }}</p>
    </x-work.description>
  @endif

  <div class="md:grid md:grid-cols-12 lg:hidden mb-40">
    <div class="md:col-span-9 md:col-start-4">
      <x-work.info
        :items="$projectInfo"
        :header="$header"
      />
    </div>
  </div>

  <x-work.section title="Grundrisse" />
  <x-slideshow.wrapper class="mb-40 lg:mb-80">
    <x-slot:info>
      &nbsp;
    </x-slot:info>
    @foreach($slides->take(3) as $slide)
      <x-slideshow.slide
        :src="$slide['src']"
        :width="$slide['width']"
        :height="$slide['height']"
        :caption="$slide['caption']"
      />
    @endforeach
  </x-slideshow.wrapper>

  <x-work.section title="Links" class="mb-40 lg:mb-80">
    <x-container.inner class="max-w-prose hyphens-auto">
      <div class="flex flex-col gap-y-6 md:gap-y-8 lg:gap-y-12">
        <x-links.cta href="#" target="_blank" label="AW20 Architekturpreis Region Winterthur">
          AW20 Architekturpreis Region Winterthur
        </x-links.cta>
        <x-links.cta href="#" target="_blank" label="Architekturpreis Kanton Zürich Auszeichnung 19">
          Architekturpreis Kanton Zürich Auszeichnung 19
        </x-links.cta>
        <x-links.cta href="#" target="_blank" label="werk, bauen+wohnen 10-2018, Dorfbau">
          werk, bauen+wohnen 10-2018, Dorfbau
        </x-links.cta>
      </div>
    </x-container.inner>
  </x-work.section>

  <x-work.section title="Team">
    <x-container.inner class="max-w-prose leading-[1.6] md:leading-[1.35]">
      <span><a href="{{ route('page.about.team') }}#boris-brunner" class="underline underline-offset-4 md:underline-offset-6 decoration-1 hover:no-underline">Boris Brunner</a>,</span> <span><a href="{{ route('page.about.team') }}#eva-geering" class="underline underline-offset-4 md:underline-offset-6 decoration-1 hover:no-underline">Eva Geering</a>,</span>
      <span><a href="{{ route('page.about.team') }}#fabian-friedli" class="underline underline-offset-4 md:underline-offset-6 decoration-1 hover:no-underline">Fabian Friedli</a>,</span> <span><a href="{{ route('page.about.team') }}#iris-bergamaschi" class="underline underline-offset-4 md:underline-offset-6 decoration-1 hover:no-underline">Iris Bergamaschi</a>,</span>
      <span><a href="{{ route('page.about.team') }}#rene-breuer" class="underline underline-offset-4 md:underline-offset-6 decoration-1 hover:no-underline">René Breuer</a>,</span> <span><a href="{{ route('page.about.team') }}#tamas-ozvald" class="underline underline-offset-4 md:underline-offset-6 decoration-1 hover:no-underline">Tamas Ozvald</a>,</span>
      <span><a href="{{ route('page.about.team') }}#roger-weber" class="underline underline-offset-4 md:underline-offset-6 decoration-1 hover:no-underline">Roger Weber</a></span>
    </x-container.inner>
  </x-work.section>

</x-layout.show>
