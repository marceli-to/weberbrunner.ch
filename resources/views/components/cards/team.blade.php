@props([
  'media' => null,
  'firstname' => '',
  'name' => '',
  'title' => '',
  'since' => '',
  'email' => '',
  'slug' => null,
])

<div class="flex flex-col p-20 pb-25">

  @if($media)
    <x-media.image
      :media="$media"
      :alt="$firstname . ' ' . $name"
      sizes="(min-width: 1024px) 25vw, (min-width: 768px) 35vw, 70vw"
      :max-width="1024"
      class="w-full aspect-3/4 object-cover max-w-[70%] mx-auto mb-20"
    />
  @endif
  
  <div class="font-semibold text-xs md:text-xxs lg:text-sm flex flex-col">
    <x-headings.h2>
      @if($slug)
        <a href="{{ route('page.about.team.show', $slug) }}" class="underline underline-offset-4 decoration-1 hover:no-underline">{{ $firstname }} {{ $name }}</a>
      @else
        {{ $firstname }} {{ $name }}
      @endif
    </x-headings.h2>
    @if($title)
      <span>{{ $title }}</span>
    @endif
    @if($since)
      <span>Mitarbeit seit {{ $since }}</span>
    @endif
    @if($email)
      <a 
        href="mailto:{{ $email }}" 
        class="underline underline-offset-4 decoration-1 hover:no-underline">
        E-Mail
      </a>
    @endif
  </div>

</div>
