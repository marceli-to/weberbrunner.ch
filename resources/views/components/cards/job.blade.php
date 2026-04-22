@props([
  'title' => null,
  'email' => null,
])

<x-container.inner class="mb-24 md:mb-40 lg:mb-56 last:!mb-0">
  <x-headings.h2 class="font-semibold mb-16 lg:mb-24">
    {{ $title }}
  </x-headings.h2>
  <article class="max-w-prose hyphens-auto">
    {{ $slot }}
  </article>
  @if ($email)
  <div class="mt-12 md:mt-20 lg:mt-28">
    Wir freuen uns auf Deine Bewerbung:<br>
    <x-links.cta href="mailto:{{ $email }}" label="Bewerbung per Mail an: {{ $email }}">
      {{ $email }}
    </x-links.cta>
  </div>
  @endif
</x-container.inner>
