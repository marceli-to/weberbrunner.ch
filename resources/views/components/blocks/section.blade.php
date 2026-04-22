@props([
  'title' => null,
  'class' => '',
])

<div {{ $attributes->merge(['class' => 'md:grid md:grid-cols-12 ' . $class]) }}>

  <div class="md:col-span-9 md:col-start-4">

    @if($title)
      <x-headings.section class="mb-8 md:mb-16 lg:mb-20">
        {{ $title }}
      </x-headings.section>
    @endif

    {{ $slot }}

  </div>

</div>
