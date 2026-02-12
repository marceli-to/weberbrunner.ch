<x-layout.guest>
	@if ($errors->any())
		<x-form.status type="error">
			Zurücksetzen fehlgeschlagen, bitte versuchen Sie es erneut.
		</x-form.status>
	@endif

	<form method="POST" action="{{ route('password.store') }}" class="mt-40 col-span-3">
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
				<x-form.input-text id="email" type="email" name="email" :value="old('email', $request->email)" placeholder="E-Mail" required autofocus autocomplete="username" />
			</div>

			<div>
				<x-form.input-password id="password" name="password" placeholder="Neues Passwort" required autocomplete="new-password" />
			</div>

			<div>
				<x-form.input-password id="password_confirmation" name="password_confirmation" placeholder="Passwort bestätigen" required autocomplete="new-password" />
			</div>

			<div>
				<x-form.button>
					Passwort zurücksetzen
				</x-form.button>
			</div>

		</div>
	</form>
</x-layout.guest>
