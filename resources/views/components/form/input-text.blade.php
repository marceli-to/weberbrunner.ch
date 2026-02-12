@props(['error' => null])

<input {{ $attributes }} class="form-input {{ $error ? 'has-error' : '' }}" />
