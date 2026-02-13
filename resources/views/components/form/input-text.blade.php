@props(['error' => null])

<input {{ $attributes }} class="form-input form-input--sm {{ $error ? 'has-error' : '' }}" />
