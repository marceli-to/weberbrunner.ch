<script setup>
import { computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import Arrow from '@/components/icons/Arrow.vue'

const route = useRoute()
const router = useRouter()

const allRoutes = computed(() => router.getRoutes())

const mainItems = computed(() => {
	const direct = allRoutes.value
		.filter(r => r.meta?.navSection === 'main' && r.meta?.navLabel)
		.map(r => ({
			name: r.name,
			label: r.meta.navLabel,
			order: r.meta.navOrder ?? 0,
			children: null,
		}))

	const promoted = allRoutes.value
		.filter(r => r.meta?.navMain)
		.map(r => ({
			name: r.name,
			label: r.meta.navMain.label,
			order: r.meta.navMain.order ?? 0,
			children: r.meta.navSection,
		}))

	return [...direct, ...promoted].sort((a, b) => a.order - b.order)
})

const getChildren = (section) =>
	allRoutes.value
		.filter(r => r.meta?.navSection === section && r.meta?.navLabel)
		.sort((a, b) => (a.meta?.navOrder ?? 0) - (b.meta?.navOrder ?? 0))
		.map(r => ({ name: r.name, label: r.meta.navLabel }))

const isSectionActive = (section) =>
	route.meta.navSection === section

const isItemActive = (item) =>
	item.children ? isSectionActive(item.children) : route.name === item.name

const isChildActive = (name) =>
	route.name === name || route.meta.navParent === name
</script>

<template>
	<aside class="col-span-2 pl-20 border-r border-black">

		<nav class="pt-30 sticky top-100">

			<ul class="flex flex-col gap-y-2">
        
				<li v-for="item in mainItems" :key="item.name">

					<RouterLink
						:to="{ name: item.name }"
						class="block py-8 text-md font-semibold"
						:class="isItemActive(item) ? 'underline underline-offset-4' : ''">
						{{ item.label }}
					</RouterLink>

					<ul v-if="item.children && isSectionActive(item.children)" class="flex flex-col gap-y-2 pl-20">

						<li v-for="child in getChildren(item.children)" :key="child.name">

							<RouterLink
								:to="{ name: child.name }"
								class="relative block py-6 text-base font-semibold">
								<Arrow v-if="isChildActive(child.name)" variant="right" size="lg" class="absolute -left-20 top-1/2 -translate-y-1/2 w-15 h-12" />
								{{ child.label }}
							</RouterLink>

						</li>

					</ul>

				</li>

			</ul>

		</nav>

	</aside>
</template>
