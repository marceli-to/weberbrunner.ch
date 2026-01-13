@props([
  'url' => null,
  'title' => null,
  'active' => false,
  'level' => 1,
])

<li {{ $attributes->merge(['class' => '']) }}>

  <a
    href="{{ $url }}"
    title="{{ $title }}"
    @class([
      'font-semibold group',
      'text-3xl md:text-4xl underline-offset-8 decoration-2' => $level == 1,
      'underline' => $active && $level == 1,
      'no-underline hover:underline' => !$active && $level == 1,
      'text-md md:text-lg lg:text-xl flex items-center' => $level == 2,
    ])>

    @if ($level == 2)
      <span
        @class([
          'inline-flex items-center overflow-hidden transition-all ease-in-out w-0 opacity-0 duration-300', 
          'w-20 opacity-100 translate-x-0' => $active,
          'group-hover:w-20 group-hover:opacity-100 group-hover:translate-x-0 group-hover:duration-300',
        ])>
        <x-icons.arrow-right size="sm" class="h-auto w-14" />
      </span>
    @endif

    <span>
      {{ $title }}
    </span>
    
  </a>

</li>
