<script setup>
import axios from 'axios'
import LogoMark from '@/components/icons/LogoMark.vue'
import LogoWBA from '@/components/icons/LogoWBA.vue'
import LogoWBPA from '@/components/icons/LogoWBPA.vue'

function logout() {
	const token = document.querySelector('meta[name="csrf-token"]')?.content
	axios.post('/logout', {}, {
		headers: { 'X-CSRF-TOKEN': token }
	}).then(() => {
		window.location.href = '/login'
	})
}
</script>

<template>
	<header class="bg-white min-h-100 sticky top-0 left-0 z-10">
		<div class="w-full h-20"></div>
		<div class="min-h-80 grid grid-cols-12 gap-x-20">

			<div class="col-span-2 pl-20 border-r border-black">
				<LogoMark class="w-25 h-25 -mt-5" />
			</div>

			<div class="col-span-8 col-start-4">
				<div class="flex justify-between">

					<h1 class="text-lg font-semibold text-black leading-none">
						DataHub
					</h1>

					<!-- this will be visible only on / route in dashboard -->
					<div class="flex items-start gap-x-40">
						<LogoWBA class="w-full h-auto max-w-150" />
						<LogoWBPA class="w-full h-auto max-w-150" />
					</div>
					<!-- // this will be visible only on / route in dashboard -->

					<!-- here will be other parts depending on the current route in dashboard -->

					<!-- // here will be other parts depending on the current route in dashboard -->
				</div>
			</div>

		</div>
		<button @click="logout" class="fixed bottom-20 left-20 text-sm underline cursor-pointer">
			Logout
		</button>
	</header>
</template>
