@props(['size' => 'sm'])

@if ($size === 'sm')
  <svg width="11" height="10" viewBox="0 0 11 10" fill="none" xmlns="http://www.w3.org/2000/svg" {{ $attributes->merge(['class' => '']) }}>
    <path d="M5.32 0L3.9 1.38L6.56 4H0V5.98H6.54L3.9 8.6L5.32 9.96L10.3 4.98L5.32 0Z" fill="currentColor"/>
  </svg>
@else
  <svg width="50" height="46" viewBox="0 0 50 46" fill="none" xmlns="http://www.w3.org/2000/svg" {{ $attributes->merge(['class' => '']) }}>
    <path d="M27.09 0L23.6899 3.29999L41.1699 20.48H0V25.14H41.36L23.79 42.52L27.09 45.72L50 22.91L27.09 0Z" fill="currentColor"/>
  </svg>
@endif
