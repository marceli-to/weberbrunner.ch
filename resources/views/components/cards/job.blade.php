@props([
  'title' => null,
  'email' => null,
])

<x-container.inner class="max-w-prose hyphens-auto">
  <x-headings.h3>
    {{ $title }}
  </x-headings.h3>
  {{ $slot }}
  <div>
    Wir freuen uns auf Deine Bewerbung:<br>
    <x-links.cta href="mailto:{{ $email }}" label="Bewerbung per Mail an: {{ $email }}">
      {{ $email }}
    </x-links.cta>
  </div>
</x-container.inner>
