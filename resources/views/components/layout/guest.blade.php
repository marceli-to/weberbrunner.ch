<x-layout.partials.app.head />

<x-layout.partials.app.body>
	<div class="min-h-screen flex flex-col">

		<header class="bg-white min-h-100 sticky top-0 left-0 z-10">
			<div class="w-full h-20"></div>

			<div class="min-h-80 grid grid-cols-12 gap-x-20">

				<div class="col-span-2 pl-20 border-r border-black">
					<x-icons.logo.symbol class="w-25 h-25 -mt-5" />
				</div>

				<div class="col-span-8 col-start-4">
					<div class="flex justify-between">
						<h1 class="text-lg font-semibold text-black leading-none">
							DataHub
						</h1>
						<div class="flex items-start gap-x-40">
							<x-icons.logo.wa class="w-full h-auto max-w-150" />
							<x-icons.logo.wpa class="w-full h-auto max-w-150" />
						</div>
					</div>
				</div>

			</div>
		</header>

		<div class="bg-snow grid grid-cols-12 gap-x-20 flex-1">
			
      <sidebar class="col-span-2 bg-snow pl-20 border-r border-black">
        &nbsp;
      </sidebar>

			<main class="col-span-9 col-start-4 grid grid-cols-9 gap-x-20 bg-snow relative">
				{{ $slot }}
			</main>
		</div>

	</div>
</x-layout.partials.app.body>

<x-layout.partials.app.footer />
