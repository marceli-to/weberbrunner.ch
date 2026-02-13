<script setup>
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import Arrow from '@/components/icons/Arrow.vue'

const route = useRoute()

const isOfficeActive = computed(() => route.name?.startsWith('office.'))
</script>

<template>
	<aside class="col-span-2 pl-20 border-r border-black">
		<nav class="pt-30 sticky top-100">
			<ul class="flex flex-col gap-y-2">
				<li>
					<RouterLink
						:to="{ name: 'projects.index' }"
						class="block py-8 text-md font-semibold"
						:class="route.name === 'projects.index' ? 'underline underline-offset-4' : ''"
					>
						Arbeiten
					</RouterLink>
				</li>
				<li>
					<RouterLink
						:to="{ name: 'office.team' }"
						class="block py-8 text-md font-semibold"
						:class="isOfficeActive ? 'underline underline-offset-4' : ''"
					>
						Büro
					</RouterLink>
					<ul v-if="isOfficeActive" class="flex flex-col gap-y-2 pl-20">
						<li>
							<RouterLink
								:to="{ name: 'office.team' }"
								class="relative block py-6 text-base font-semibold"
							>
								<Arrow v-if="route.name === 'office.team'" variant="right" size="lg" class="absolute -left-20 top-1/2 -translate-y-1/2 w-15 h-12" />
								Team
							</RouterLink>
						</li>
						<li>
							<RouterLink
								:to="{ name: 'office.jobs' }"
								class="relative block py-6 text-base font-semibold"
							>
								<Arrow v-if="route.name === 'office.jobs'" variant="right" size="lg" class="absolute -left-20 top-1/2 -translate-y-1/2 w-15 h-12" />
								Jobs
							</RouterLink>
						</li>
						<li>
							<RouterLink
								:to="{ name: 'office.network' }"
								class="relative block py-6 text-base font-semibold"
							>
								<Arrow v-if="route.name === 'office.network'" variant="right" size="lg" class="absolute -left-20 top-1/2 -translate-y-1/2 w-15 h-12" />
								Netzwerk
							</RouterLink>
						</li>
						<li>
							<RouterLink
								:to="{ name: 'office.talks' }"
								class="relative block py-6 text-base font-semibold"
							>
								<Arrow v-if="route.name === 'office.talks'" variant="right" size="lg" class="absolute -left-20 top-1/2 -translate-y-1/2 w-15 h-12" />
								Vorträge
							</RouterLink>
						</li>
						<li>
							<RouterLink
								:to="{ name: 'office.jury' }"
								class="relative block py-6 text-base font-semibold"
							>
								<Arrow v-if="route.name === 'office.jury'" variant="right" size="lg" class="absolute -left-20 top-1/2 -translate-y-1/2 w-15 h-12" />
								Jury
							</RouterLink>
						</li>
						<li>
							<RouterLink
								:to="{ name: 'office.awards' }"
								class="relative block py-6 text-base font-semibold"
							>
								<Arrow v-if="route.name === 'office.awards'" variant="right" size="lg" class="absolute -left-20 top-1/2 -translate-y-1/2 w-15 h-12" />
								Auszeichnungen
							</RouterLink>
						</li>
					</ul>
				</li>
				<li>
					<RouterLink
						:to="{ name: 'settings.index' }"
						class="block py-8 text-md font-semibold"
						:class="route.name === 'settings.index' ? 'underline underline-offset-4' : ''"
					>
						Voreinstellungen
					</RouterLink>
				</li>
				<li>
					<RouterLink
						:to="{ name: 'profile.index' }"
						class="block py-8 text-md font-semibold"
						:class="route.name === 'profile.index' ? 'underline underline-offset-4' : ''"
					>
						Profil
					</RouterLink>
				</li>
			</ul>
		</nav>
	</aside>
</template>
