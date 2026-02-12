@php $uid = 'pw-' . uniqid(); @endphp

<div class="relative">

  <input {{ $attributes->merge(['type' => 'password']) }} data-pw-input="{{ $uid }}" />

  <button type="button" data-pw-toggle="{{ $uid }}" class="absolute right-0 top-1/2 -translate-y-1/2 right-10">

    <span data-pw-icon-off="{{ $uid }}" class="w-16 h-16 flex items-center justify-center">
      <x-icons.auth.eye-off class="w-16 h-auto" />
    </span>

    <span data-pw-icon-on="{{ $uid }}" class="w-16 h-16 flex items-center justify-center" style="display:none;">
      <x-icons.auth.eye-on class="w-16 h-auto" />
    </span>

  </button>

</div>

<script>
	document.querySelector('[data-pw-toggle="{{ $uid }}"]').addEventListener('click', function () {
		var input = document.querySelector('[data-pw-input="{{ $uid }}"]');
		var off = document.querySelector('[data-pw-icon-off="{{ $uid }}"]');
		var on = document.querySelector('[data-pw-icon-on="{{ $uid }}"]');
		var isPassword = input.type === 'password';
		input.type = isPassword ? 'text' : 'password';
		off.style.display = isPassword ? 'none' : '';
		on.style.display = isPassword ? '' : 'none';
	});
</script>
