@props([
  'image' => null,
  'firstname' => '',
  'name' => '',
  'title' => '',
  'employedSince' => '',
  'email' => '',
])

<div class="flex flex-col p-20 pb-25">
  <x-media.image 
    :src="$image" 
    :alt="$firstname . ' ' . $name" 
    class="w-full aspect-3/4 object-cover max-w-[70%] mx-auto mb-20"
  />
  <div class="font-semibold text-xs md:text-xxs lg:text-sm flex flex-col">
    <x-headings.h2>
      {{ $firstname }} {{ $name }}
    </x-headings.h2>
    @if($title)
      <span>{{ $title }}</span>
    @endif
    @if($employedSince)
      <span>Mitarbeit seit {{ $employedSince }}</span>
    @endif
    @if($email)
      <a 
        href="mailto:{{ $email }}" 
        class="underline underline-offset-8 decoration-1 hover:no-underline">
        E-Mail
      </a>
    @endif
  </div>
</div>
