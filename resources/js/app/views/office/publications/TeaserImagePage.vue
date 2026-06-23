<script setup>
import { computed } from 'vue'
import publicationsApi from '@/api/publications'
import mediaApi from '@/api/media'
import { usePublication } from '@/composables/usePublication'
import { useCan } from '@/composables/useCan'
import PublicationLayout from '@/views/office/publications/components/Layout.vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import SectionTitle from '@/components/ui/SectionTitle.vue'
import MediaCard from '@/components/media/MediaCard.vue'
import MediaUploader from '@/components/media/MediaUploader.vue'

const { publication, fetch } = usePublication()
const { canUpload, canDelete } = useCan()

const teaserImage = computed(() => publication.value?.media?.find(m => m.is_teaser) || null)

async function uploadTeaser(media) {
	await publicationsApi.attachMedia(publication.value.uuid, [media])
	await mediaApi.teaser(media.uuid)
	await fetch()
}

async function removeTeaser() {
	if (!teaserImage.value) return
	await mediaApi.update(teaserImage.value.uuid, { is_teaser: false })
	await fetch()
}
</script>

<template>
	<PublicationLayout :publication="publication">

		<Grid class="mb-20">
			<Span class="col-span-8 col-start-2">
				<SectionTitle>Teaserbild</SectionTitle>
			</Span>
			<Span class="col-span-2 col-start-2">
				<div v-if="teaserImage">
					<MediaCard :item="teaserImage" :deletable="canDelete" @delete="removeTeaser" />
				</div>
				<MediaUploader v-else-if="canUpload" @uploaded="uploadTeaser" />
			</Span>
		</Grid>

	</PublicationLayout>
</template>
