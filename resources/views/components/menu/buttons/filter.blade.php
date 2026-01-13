<button 
  @click="filter = !filter"
  {{ $attributes->merge(['class' => 'w-25 h-auto cursor-pointer']) }}>
  <x-icons.filter variant="outline" class="w-full h-auto" />
</button>