@props(['size' => 'lg'])

@if($size === 'lg')
  <svg width="440" height="441" viewBox="0 0 440 441" fill="none" xmlns="http://www.w3.org/2000/svg" {{ $attributes->merge(['class' => '']) }}>
    <path d="M222.551 217.45V0H440.001C438.231 119.36 341.911 215.69 222.551 217.45Z" fill="currentColor"/>
    <path d="M440.001 440H222.551V222.55C341.911 224.31 438.241 320.64 440.001 440Z" fill="currentColor"/>
    <path d="M217.45 0.0302734V217.48H0C1.77 98.1203 98.09 1.79027 217.45 0.0302734Z" fill="currentColor"/>
    <path d="M0 440.03V222.58H217.45C215.68 341.94 119.36 438.27 0 440.03Z" fill="currentColor"/>
  </svg>
@else
  <svg width="41" height="41" viewBox="0 0 41 41" fill="none" xmlns="http://www.w3.org/2000/svg" {{ $attributes->merge(['class' => '']) }}>
    <path d="M21.2305 19.77V0H41.0005C40.8405 10.85 32.0805 19.61 21.2305 19.77Z" fill="currentColor"/>
    <path d="M41.0005 41.0005H21.2305V21.2305C32.0805 21.3905 40.8405 30.1505 41.0005 41.0005Z" fill="currentColor"/>
    <path d="M19.77 0V19.77H0C0.16 8.92002 8.92 0.16 19.77 0Z" fill="currentColor"/>
    <path d="M0 41.0005V21.2305H19.77C19.61 32.0805 10.85 40.8405 0 41.0005Z" fill="currentColor"/>
  </svg>
@endif
