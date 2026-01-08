<a
  href="javascript:;"
  x-on:click="menu = !menu"
  aria-label="Menü anzeigen"
  {{ $attributes->merge(['class' => 'block']) }}>
  <x-icons.burger class="w-full h-auto" />
</a>
