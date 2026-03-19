<script setup>
import { ref, computed, watch } from 'vue'
import publicationsApi from '@/api/publications'
import mediaApi from '@/api/media'
import { usePublication } from '@/composables/usePublication'
import { useCollapsed } from '@/composables/useCollapsed'
import { useToast } from '@/composables/useToast'
import PublicationLayout from '@/views/office/publications/components/Layout.vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import CollapsibleHeader from '@/components/ui/CollapsibleHeader.vue'
import MediaCard from '@/components/media/MediaCard.vue'
import AddButton from '@/components/media/AddButton.vue'
import Textarea from '@/components/ui/form/Textarea.vue'
import Button from '@/components/ui/form/Button.vue'

const { publication, fetch } = usePublication()
const toast = useToast()
const { collapsed, toggle } = useCollapsed('publication-metadata')

const metaDescription = ref('')

watch(() => publication.value?.meta_description, (val) => {
	metaDescription.value = val || ''
}, { immediate: true })

const ogImage = computed(() => publication.value?.media?.find(m => m.is_og) || null)

async function saveMetaDescription() {
	await publicationsApi.update(publication.value.uuid, {
		title: publication.value.title,
		meta_description: metaDescription.value || null,
	})
	await fetch()
	toast.success('Gespeichert')
}

async function uploadOgImage(event) {
	const file = event.target.files[0]
	if (!file) return
	event.target.value = ''
	const fd = new FormData()
	fd.append('file', file)
	const { data: { data: tempItem } } = await mediaApi.upload(fd)
	await publicationsApi.attachMedia(publication.value.uuid, [tempItem])
	await mediaApi.og(tempItem.uuid)
	await fetch()
}

async function removeOgImage() {
	if (!ogImage.value) return
	await mediaApi.update(ogImage.value.uuid, { is_og: false })
	await fetch()
}
</script>

<template>
	<PublicationLayout :publication="publication">

		<!-- Meta Description -->
		<Grid class="mb-20">
			<Span class="col-span-8 col-start-2">
				<CollapsibleHeader
					title="Meta Description"
					:collapsed="collapsed.has('meta')"
					@toggle="toggle('meta')" />
			</Span>
			<Span v-show="!collapsed.has('meta')" class="col-span-8 col-start-2">
				<form @submit.prevent="saveMetaDescription">
					<Textarea v-model="metaDescription" />
					<div class="flex gap-20 mt-10">
						<Button type="submit" class="flex justify-center">Speichern</Button>
					</div>
				</form>
			</Span>
		</Grid>

		<!-- Open Graph Image -->
		<Grid class="mb-20">
			<Span class="col-span-8 col-start-2">
				<CollapsibleHeader
					title="Open Graph Image"
					:collapsed="collapsed.has('og')"
					@toggle="toggle('og')" />
			</Span>
			<Span v-show="!collapsed.has('og')" class="col-span-2 col-start-2">
				<div v-if="ogImage">
					<MediaCard :item="ogImage" deletable @delete="removeOgImage" />
				</div>
				<label v-else class="cursor-pointer">
					<AddButton @click.prevent />
					<input type="file" accept="image/*" class="hidden" @change="uploadOgImage" />
				</label>
			</Span>
		</Grid>

	</PublicationLayout>
</template>
