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
          :media="$slide"
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

  <x-blocks.container :blocks="$project->blocks" />

</x-layout.show>
