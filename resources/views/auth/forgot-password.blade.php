<x-layout.guest>
	@if (session('status'))
		<x-form.status>{{ session('status') }}</x-form.status>
	@endif

	@if ($errors->any())
		<x-form.status type="error">
			Anfrage fehlgeschlagen, bitte versuchen Sie es erneut.
		</x-form.status>
	@endif

	<form method="POST" action="{{ route('password.email') }}" class="mt-40 col-span-3" data-auth-form>
		@csrf

		<div class="flex flex-col gap-y-10">

			<div class="flex justify-between">

				<h1 class="font-semibold">
					Passwort vergessen
				</h1>

				<a
					href="{{ route('login') }}"
					class="font-semibold text-black flex items-center gap-x-5">
					<x-icons.auth.arrow-right class="w-10 h-auto" />
					Login
				</a>
			</div>

			<div>
				<x-form.input-text id="email" type="email" name="email" :value="old('email')" placeholder="E-Mail" :error="$errors->first('email')" />
			</div>

			<div>
				<x-form.button class="px-10">
					Link senden
				</x-form.button>
			</div>

		</div>
	</form>
</x-layout.guest>
