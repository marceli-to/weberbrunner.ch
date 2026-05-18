<script setup>
import { ref } from 'vue'
import pageTextApi from '@/api/pageText'
import { usePageLoader } from '@/composables/usePageLoader'
import PageTitle from '@/components/ui/PageTitle.vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import PageBlocks from '@/components/blocks/PageBlocks.vue'

const { load } = usePageLoader()

const page = ref(null)

async function fetch() {
	const { data } = await pageTextApi.show('network')
	page.value = data.data
}

load(fetch)
</script>

<template>

	<Grid class="mb-40">
		<Span class="col-span-8 col-start-2">
			<PageTitle>Netzwerk</PageTitle>
		</Span>
	</Grid>

	<PageBlocks v-if="page" :page="page" @updated="fetch" />

</template>
