@props(['size' => 'sm'])

@if($size === 'lg')
<svg width="43" height="40" viewBox="0 0 43 40" fill="none" xmlns="http://www.w3.org/2000/svg" {{ $attributes->merge(['class' => '']) }}>
  <path d="M19.67 0L0 19.67L19.67 39.33L23.5 35.58L10.08 22.33H42.92V17H10.08L23.5 3.75L19.67 0Z" fill="currentColor"/>
</svg>
@else
<svg width="40" height="37" viewBox="0 0 40 37" fill="none" xmlns="http://www.w3.org/2000/svg" {{ $attributes->merge(['class' => '']) }}>
  <path d="M18.33 0L0 18.33L18.33 36.66L21.9 33.17L9.40002 20.82H40V15.85H9.40002L21.9 3.5L18.33 0Z" fill="currentColor"/>
</svg>
@endif
