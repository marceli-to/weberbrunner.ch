@props([
  'title' => 'Projektbeschrieb',
])

<x-blocks.section :title="$title" class="mb-40 lg:mb-80">
  <x-container.inner>
    <article class="max-w-prose hyphens-auto">
      {{ $slot }}
    </article>
  </x-container.inner>
</x-blocks.section>
