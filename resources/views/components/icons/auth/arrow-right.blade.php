@props(['size' => 'sm'])

@if ($size === 'lg')
  <svg {{ $attributes }} viewBox="0 0 15 12" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M9 0L8.14996 0.850006L12.7 5.40002H0V6.60001H12.7L8.14996 11.15L9 12L15 6L9 0Z" fill="currentColor"/>
  </svg>
@else
  <svg {{ $attributes }} viewBox="0 0 10 8" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M6 0L5.28998 0.700012L8.08002 3.5H0V4.5H8.08002L5.28998 7.29001L6 8L10 4L6 0Z" fill="currentColor"/>
  </svg>
@endif
