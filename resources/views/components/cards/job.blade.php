@props([
  'title' => null,
  'email' => null,
])

<x-container.inner class="max-w-prose hyphens-auto [&_h2]:font-semibold [&_h2]:mb-16 lg:[&_h2]:mb-24 [&_h3]:font-semibold [&_ul]:list-disc [&_ul]:pl-[1.5em] [&_a[href]:not([href^='mailto:'])]:underline [&_a[href]:not([href^='mailto:'])]:underline-offset-4 md:[&_a[href]:not([href^='mailto:'])]:underline-offset-6 [&_a[href]:not([href^='mailto:'])]:decoration-1 [&_a:hover]:no-underline mb-24 md:mb-40 lg:mb-56 last:!mb-0">
  <x-headings.h2>
    {{ $title }}
  </x-headings.h2>
  {{ $slot }}
  <div>
    Wir freuen uns auf Deine Bewerbung:<br>
    <x-links.cta href="mailto:{{ $email }}" label="Bewerbung per Mail an: {{ $email }}">
      {{ $email }}
    </x-links.cta>
  </div>
</x-container.inner>
