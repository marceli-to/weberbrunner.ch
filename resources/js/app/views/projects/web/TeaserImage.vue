<script setup>
import { useRoute, useRouter } from 'vue-router'
import { useProjectTeaser } from '@/composables/useProjectTeaser'
import PageTitle from '@/components/ui/PageTitle.vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import BackButton from '@/components/ui/BackButton.vue'
import WebNavBar from '@/views/projects/components/navbar/Web.vue'
import SectionTitle from '@/components/ui/SectionTitle.vue'
import MediaCard from '@/components/media/MediaCard.vue'
import AddButton from '@/components/media/AddButton.vue'
import MediaPickerDrawer from '@/components/media/MediaPickerDrawer.vue'

const route = useRoute()
const router = useRouter()
const {
	project, teaserImage, selectedTeaserImage, teaserDrawerOpen,
	saveTeaserImage, removeTeaserImage,
} = useProjectTeaser()

function goBack() {
	router.push({ name: 'projects.show', params: { id: route.params.id } })
}
</script>

<template>

	<template v-if="project">

    <!-- NavBar -->
    <Grid class="mb-40">
      <Span class="col-span-8 col-start-2">
        <WebNavBar />
      </Span>
    </Grid>

    <!-- Header -->
    <Grid class="mb-20">

      <Span class="col-span-1 flex items-center justify-center">
        <BackButton @click="goBack" />
      </Span>

      <Span class="col-span-8">
        <PageTitle>
          {{ project.full_title }}
        </PageTitle>
      </Span>

    </Grid>

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

	</template>

</template>
