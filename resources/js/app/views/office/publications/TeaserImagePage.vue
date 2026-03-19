<script setup>
import { computed } from 'vue'
import publicationsApi from '@/api/publications'
import mediaApi from '@/api/media'
import { usePublication } from '@/composables/usePublication'
import PublicationLayout from '@/views/office/publications/components/Layout.vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import SectionTitle from '@/components/ui/SectionTitle.vue'
import MediaCard from '@/components/media/MediaCard.vue'
import AddButton from '@/components/media/AddButton.vue'

const { publication, fetch } = usePublication()

const teaserImage = computed(() => publication.value?.media?.find(m => m.is_teaser) || null)

async function uploadTeaser(event) {
	const file = event.target.files[0]
	if (!file) return
	event.target.value = ''
	const fd = new FormData()
	fd.append('file', file)
	const { data: { data: tempItem } } = await mediaApi.upload(fd)
	await publicationsApi.attachMedia(publication.value.uuid, [tempItem])
	await mediaApi.teaser(tempItem.uuid)
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
					<MediaCard :item="teaserImage" deletable @delete="removeTeaser" />
				</div>
				<label v-else class="cursor-pointer">
					<AddButton @click.prevent />
					<input type="file" accept="image/*" class="hidden" @change="uploadTeaser" />
				</label>
			</Span>
		</Grid>

	</PublicationLayout>
</template>
