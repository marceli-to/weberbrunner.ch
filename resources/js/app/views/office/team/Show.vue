<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import teamApi from '@/api/team'
import PageTitle from '@/components/ui/PageTitle.vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import Arrow from '@/components/icons/Arrow.vue'
import TeamImage from './components/Image.vue'
import Profile from './components/Profile.vue'
import Bio from './components/Bio.vue'

const route = useRoute()
const router = useRouter()
const member = ref(null)

async function fetch() {
	const { data } = await teamApi.show(route.params.id)
	member.value = data.data
}

function goBack() {
	router.push({ name: 'office.team' })
}

onMounted(fetch)
</script>

<template>
	<template v-if="member">

		<!-- Header -->
		<Grid class="mb-20">
			<Span class="col-span-1 flex items-center justify-center">
				<button type="button" @click="goBack">
					<Arrow variant="left" class="w-25 cursor-pointer" />
				</button>
			</Span>
			<Span class="col-span-8">
				<PageTitle>
					{{ member.fullname }}
				</PageTitle>
			</Span>
		</Grid>

		<!-- Content -->
		<Grid class="mb-20">

			<Span class="col-span-2 col-start-2">
				<TeamImage :member="member" @updated="fetch" />
			</Span>

			<Span class="col-span-6 flex flex-col gap-20">
				<Profile :member="member" @updated="fetch" />
				<Bio :member="member" @updated="fetch" />
			</Span>
		</Grid>

	</template>
</template>
