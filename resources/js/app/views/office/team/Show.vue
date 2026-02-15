<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import teamApi from '@/api/team'
import PageTitle from '@/components/ui/PageTitle.vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import Arrow from '@/components/icons/Arrow.vue'

const route = useRoute()
const router = useRouter()
const member = ref(null)

onMounted(async () => {
	const { data } = await teamApi.show(route.params.id)
	member.value = data.data
})

function goBack() {
	router.push({ name: 'office.team' })
}
</script>

<template>
	<Grid class="mb-10" v-if="member">

		<!-- Header -->
		<Span class="col-span-1 flex items-center justify-center">
			<button type="button" @click="goBack">
				<Arrow variant="left" class="w-25 cursor-pointer" />
			</button>
		</Span>
		<Span class="col-span-8">
			<PageTitle>{{ member.firstname }} {{ member.name }}</PageTitle>
		</Span>

		<!-- Details -->
		<Span class="col-span-8 col-start-2">
			<p v-if="member.title">{{ member.title }}</p>
			<p v-if="member.location?.title">{{ member.location.title }}</p>
		</Span>

	</Grid>
</template>
