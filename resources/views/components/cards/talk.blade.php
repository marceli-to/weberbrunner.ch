@props([
  'title' => null,
  'link' => null,
  'link_text' => null,
])

<div class="max-w-prose text-pretty">

  <span class="font-semibold">
    «{{ $title }}»
  </span>

  {{ $slot }}

  @if($link)
    <a href="{{ $link }}" class="group">
      → <span class="underline underline-offset-4 decoration-1 group-hover:no-underline">{{ $link_text }}</span>
    </a>
  @endif
  
</div>
