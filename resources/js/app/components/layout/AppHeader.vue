<script setup>
import LogoMark from '@/components/icons/LogoMark.vue'
import Avatar from '@/components/icons/Avatar.vue'
import { useAuthStore } from '@/stores/auth'

const authStore = useAuthStore()
</script>

<template>
	<header class="bg-white min-h-100 sticky top-0 left-0 z-50">
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

					<div v-if="authStore.user" class="flex items-center gap-x-15">
						<span class="text-lg font-semibold text-black leading-none">
              {{ authStore.user.firstname }} {{ authStore.user.name }}
            </span>
						<Avatar class="w-24 h-24" />
					</div>
				</div>
			</div>

		</div>
	</header>
</template>
