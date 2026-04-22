<script setup>
import { ref } from 'vue'
import landingTextApi from '@/api/landingText'
import { usePageLoader } from '@/composables/usePageLoader'
import PageTitle from '@/components/ui/PageTitle.vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import Blocks from '@/views/office/intro/components/Blocks.vue'

const { load } = usePageLoader()

const page = ref(null)

async function fetch() {
	const { data } = await landingTextApi.show('office')
	page.value = data.data
}

load(fetch)
</script>

<template>

	<Grid class="mb-40">
		<Span class="col-span-8 col-start-2">
			<PageTitle>Intro</PageTitle>
		</Span>
	</Grid>

	<Blocks v-if="page" :page="page" @updated="fetch" />

</template>
