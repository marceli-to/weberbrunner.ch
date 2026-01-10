<body
  x-data="{ menu: false }"
  class="
    antialiased 
    font-sans
    font-normal
    text-xs
    md:text-lg
    lg:text-xl
    leading-[1.35]
    tracking-[0.005em]
    min-h-screen
    flex 
    flex-col">
    <x-layout.partials.debug />
  {{ $slot }}
</body>