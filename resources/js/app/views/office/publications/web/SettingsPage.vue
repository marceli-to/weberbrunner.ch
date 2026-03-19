<script setup>
import { ref, watch } from 'vue'
import publicationsApi from '@/api/publications'
import { usePublication } from '@/composables/usePublication'
import { useToast } from '@/composables/useToast'
import PublicationLayout from '@/views/office/publications/components/Layout.vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import PublishToggle from '@/components/ui/form/PublishToggle.vue'

const { publication, fetch } = usePublication()
const toast = useToast()

async function togglePublish() {
	await publicationsApi.toggle(publication.value.uuid)
	await fetch()
	toast.success(publication.value.publish ? 'Nicht publiziert' : 'Publiziert')
}
</script>

<template>
	<PublicationLayout :publication="publication">

		<!-- Publish -->
		<Grid class="mb-20">
			<Span class="col-span-8 col-start-2">
				<PublishToggle
					v-if="publication"
					:model-value="publication.publish"
					@update:model-value="togglePublish" />
			</Span>
		</Grid>

	</PublicationLayout>
</template>
