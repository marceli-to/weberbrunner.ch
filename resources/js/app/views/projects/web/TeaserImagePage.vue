<script setup>
import { useProjectTeaser } from '@/composables/useProjectTeaser'
import WebLayout from '@/views/projects/components/Layout.vue'
import SectionTitle from '@/components/ui/SectionTitle.vue'
import MediaCard from '@/components/media/MediaCard.vue'
import AddButton from '@/components/media/AddButton.vue'
import MediaPickerDrawer from '@/components/media/MediaPickerDrawer.vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'

const {
	project, teaserImage, selectedTeaserImage, teaserDrawerOpen,
	saveTeaserImage, removeTeaserImage,
} = useProjectTeaser()
</script>

<template>
	<WebLayout :project="project">

		<!-- Teaser Image -->
		<Grid class="mb-20">
			<Span class="col-span-8 col-start-2">
				<SectionTitle>Teaserbild</SectionTitle>
			</Span>
			<Span class="col-span-2 col-start-2">
				<div v-if="teaserImage">
					<MediaCard :item="teaserImage" deletable editable @delete="removeTeaserImage" @edit="teaserDrawerOpen = true" />
				</div>
				<AddButton v-else @click="teaserDrawerOpen = true" />
			</Span>
		</Grid>

		<MediaPickerDrawer
			:open="teaserDrawerOpen"
			:items="project.media"
			v-model="selectedTeaserImage"
			@close="teaserDrawerOpen = false"
			@submit="saveTeaserImage" />

	</WebLayout>
</template>
