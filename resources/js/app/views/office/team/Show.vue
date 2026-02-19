<script setup>
import { ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import teamApi from '@/api/team'
import { usePageLoader } from '@/composables/usePageLoader'
import PageTitle from '@/components/ui/PageTitle.vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import Arrow from '@/components/icons/Arrow.vue'
import TeamImage from './components/TeamImage.vue'
import TeamProfile from './components/TeamProfile.vue'
import TeamBio from './components/TeamBio.vue'

const route = useRoute()
const router = useRouter()
const { load } = usePageLoader()
const member = ref(null)

async function fetch() {
	const { data } = await teamApi.show(route.params.id)
	member.value = data.data
}

function goBack() {
	router.push({ name: 'office.team' })
}

load(fetch)
</script>

<template>
	<!-- Header -->
	<Grid class="mb-20">
		<Span class="col-span-1 flex items-center justify-center">
			<button type="button" @click="goBack">
				<Arrow variant="left" class="w-25 cursor-pointer" />
			</button>
		</Span>
		<Span class="col-span-8">
			<PageTitle>
				{{ member?.fullname }}
			</PageTitle>
		</Span>
	</Grid>

	<!-- Content -->
	<Grid v-if="member" class="mb-20">

		<Span class="col-span-2 col-start-2">
			<TeamImage :member="member" @updated="fetch" />
		</Span>

		<Span class="col-span-6 flex flex-col gap-20">
			<TeamProfile :member="member" @updated="fetch" />
			<TeamBio :member="member" @updated="fetch" />
		</Span>
	</Grid>
</template>
