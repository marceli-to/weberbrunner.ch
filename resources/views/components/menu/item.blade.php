@props([
  'url' => null,
  'title' => null,
  'active' => false,
  'level' => 1,
])
<li>
  <a
    href="{{ $url }}"
    title="{{ $title }}"
    @class([
      'font-semibold',
      'text-3xl md:text-4xl underline-offset-8 decoration-2' => $level == 1,
      'text-md md:text-lg lg:text-xl flex items-center gap-x-6' => $level == 2,
      'underline' => $active && $level == 1,
      'no-underline hover:underline' => !$active && $level == 1,
    ])>
    @if ($level == 2 && $active)
      <x-icons.arrow-right size="sm" class="w-14 h-auto" />
    @endif
  {{ $title }}
  </a>
</li>
