@props([
  'href' => null,
  'label' => null,
])

<a
  href="{{ $href }}"
  @if($label) 
  aria-label="{{ $label }}"
  @endif
  {{ $attributes->merge(['class' => 'inline-flex items-center leading-none gap-x-6 underline underline-offset-4 md:underline-offset-8 decoration-1 hover:no-underline']) }}>
  <x-icons.arrow-right class="h-auto w-12 md:w-14" />
  {{ $slot }}
</a>
