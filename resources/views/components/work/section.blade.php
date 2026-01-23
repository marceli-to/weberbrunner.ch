@props([
  'title' => null,
  'class' => '',
])

<div {{ $attributes->merge(['class' => 'md:grid md:grid-cols-12 pl-20 md:pl-0 ' . $class]) }}>

  <div class="md:col-span-9 md:col-start-4">

    @if($title)
      <x-headings.section class="mb-6 md:mb-12 lg:mb-18">
        {{ $title }}
      </x-headings.section>
    @endif

    {{ $slot }}

  </div>

</div>
