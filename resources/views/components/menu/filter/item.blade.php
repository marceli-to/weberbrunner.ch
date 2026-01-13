@props([
  'title' => null,
  'active' => false,
  'action' => null,
])

<li>
  <button
    wire:click="{{ $action }}"
    type="button"
    class="font-semibold text-md md:text-lg lg:text-xl flex items-center cursor-pointer group">
    <span
      @class([
        'inline-flex items-center overflow-hidden transition-all ease-in-out w-0 opacity-0 duration-300', 
        'w-20 opacity-100 translate-x-0' => $active,
        'group-hover:w-20 group-hover:opacity-100 group-hover:translate-x-0 group-hover:duration-300',
      ])>
      <x-icons.arrow-right size="sm" class="h-auto w-14" />
    </span>
    <span>{{ $title }}</span>
  </button>
</li>
