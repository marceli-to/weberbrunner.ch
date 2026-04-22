@section('meta_title', $member->firstname . ' ' . $member->name . ' – Team')
@section('meta_description', trim(collect([$member->firstname . ' ' . $member->name, $member->title, $member->since ? 'Mitarbeit seit ' . $member->since : null])->filter()->implode(', ')))
@if($member->image?->file)
	@ogImage($member->image->file)
@endif

<x-layout.inner
  title="Team"
  containerClass="!pl-0"
  headerClass="pl-20 md:pl-0"
  mainClass="!pb-0 relative">

  <div class="md:min-h-(--content-height-md) lg:min-h-(--content-height-lg) md:border-l border-black">

    <div class="md:grid md:grid-cols-9 pb-20 lg:pb-40 border-t border-black">

      <div class="md:col-span-3 p-20 pb-25">
        @if($member->image)
          <x-media.image
            :media="$member->image"
            :alt="$member->firstname . ' ' . $member->name"
            sizes="(min-width: 768px) 33vw, 70vw"
            :max-width="1280"
            class="w-full aspect-3/4 object-cover max-w-[70%] md:max-w-none mx-auto"
          />
        @endif
        <div class="md:mt-20 lg:mt-40 hidden md:block">
          <a
            href="{{ route('page.about.team') }}"
            aria-label="Zurück zur Übersicht">
            <x-icons.cross size="lg" class="w-20 lg:w-30 h-auto" />
          </a>
        </div>
      </div>

      <div class="md:col-span-6 md:pt-20 px-20 md:pl-0">
        <div class="font-semibold text-xs md:text-lg lg:text-xl flex flex-col  mb-20 lg:mb-40">
          <x-headings.h2 class="">
            {{ $member->firstname }} {{ $member->name }}
          </x-headings.h2>
          @if($member->title)
            <span>{{ $member->title }}</span>
          @endif
          @if($member->since)
            <span>Mitarbeit seit {{ $member->since }}</span>
          @endif
          @if($member->email)
            <a
              href="mailto:{{ $member->email }}"
              class="underline underline-offset-4 md:underline-offset-6 decoration-1 hover:no-underline">
              {{ $member->email }}
            </a>
          @endif
        </div>

        <div class="text-xs lg:text-sm">

          @if($member->bios->isNotEmpty())
            <div class="space-y-15 max-w-prose w-full">
              @foreach($member->bios as $entry)
                <div class="md:grid md:grid-cols-12 md:gap-x-10 lg:gap-x-20">
                  <div class="md:col-span-3 xl:col-span-2">{{ $entry->period }}</div>
                  <div class="md:col-span-9 xl:col-span-10">{{ $entry->description }}</div>
                </div>
              @endforeach
            </div>
          @endif
        </div>

        <div class="mt-25 md:hidden">
          <a
            href="{{ route('page.about.team') }}"
            aria-label="Zurück zur Übersicht">
            <x-icons.cross size="lg" class="w-16 h-auto" />
          </a>
        </div>

      </div>

    </div>

  </div>

</x-layout.inner>
