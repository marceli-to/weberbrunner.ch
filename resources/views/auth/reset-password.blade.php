<x-layout.guest>
	@if ($errors->any())
		<x-form.status type="error">
			Zurücksetzen fehlgeschlagen, bitte versuchen Sie es erneut.
		</x-form.status>
	@endif

	<form method="POST" action="{{ route('password.store') }}" class="mt-40 col-span-3" data-auth-form>
		@csrf

		<input type="hidden" name="token" value="{{ $request->route('token') }}">

		<div class="flex flex-col gap-y-10">

			<div class="flex justify-between">

				<h1 class="font-semibold">
					Passwort zurücksetzen
				</h1>

				<a
					href="{{ route('login') }}"
					class="font-semibold text-black flex items-center gap-x-5">
					<x-icons.auth.arrow-right class="w-10 h-auto" />
					Login
				</a>
			</div>

			<div>
				<x-form.input-text id="email" type="email" name="email" :value="old('email', $request->email)" placeholder="E-Mail" :error="$errors->first('email')" autocomplete="username" />
			</div>

			<div>
				<x-form.input-password id="password" name="password" placeholder="Neues Passwort" :error="$errors->first('password')" autocomplete="new-password" />
			</div>

			<div>
				<x-form.input-password id="password_confirmation" name="password_confirmation" placeholder="Passwort bestätigen" :error="$errors->first('password_confirmation')" autocomplete="new-password" />
			</div>

			<div>
				<x-form.button class="px-10">
					Passwort zurücksetzen
				</x-form.button>
			</div>

		</div>
	</form>
</x-layout.guest>
