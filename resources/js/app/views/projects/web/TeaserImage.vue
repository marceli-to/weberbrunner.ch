<script setup>
import { ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useProjectTeaser } from '@/composables/useProjectTeaser'
import PageTitle from '@/components/ui/PageTitle.vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import BackButton from '@/components/ui/BackButton.vue'
import WebNavBar from '@/views/projects/components/navbar/Web.vue'
import SectionTitle from '@/components/ui/SectionTitle.vue'
import Drawer from '@/components/ui/drawer/Drawer.vue'
import MediaCard from '@/components/media/MediaCard.vue'
import AddButton from '@/components/media/AddButton.vue'
import RadioIcon from '@/components/icons/Radio.vue'

const route = useRoute()
const router = useRouter()
const {
	project, teaserImage, selectedTeaserImage, teaserDrawerOpen,
	saveTeaserImage, removeTeaserImage,
} = useProjectTeaser()
const drawerView = ref('list')

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

    <Drawer
      :open="teaserDrawerOpen"
      :views="[{ label: 'Text / Bilder', value: 'list' }, { label: 'Bilder', value: 'grid' }]"
      v-model:view="drawerView"
      submit-label="Übernehmen"
      cancel-label="Abbrechen"
      @close="teaserDrawerOpen = false"
      @submit="saveTeaserImage">

      <!-- List view -->
      <Grid v-if="drawerView === 'list'" :cols="6" class="mt-40">
        <Span class="col-span-4 col-start-2" v-for="item in project.media" :key="item.uuid">
          <Grid :cols="4">
            <Span class="col-span-3">
              <button type="button" class="flex items-start gap-x-10 border-t-thin border-t-white pt-10 cursor-pointer w-full text-left" @click="selectedTeaserImage = item.uuid">
                <RadioIcon
                  :variant="selectedTeaserImage === item.uuid ? 'checked' : 'unchecked'"
                  class="w-12 shrink-0 mt-2 text-white" />
                <span class="text-white text-sm overflow-hidden text-ellipsis whitespace-nowrap">{{ item.original_name }}</span>
              </button>
            </Span>
            <Span class="col-span-1">
              <img
                :src="item.thumbnail_url"
                :alt="item.original_name"
                class="w-full h-auto aspect-square object-cover bg-white" />
            </Span>
          </Grid>
        </Span>
      </Grid>

      <!-- Grid view -->
      <Grid v-if="drawerView === 'grid'" :cols="12" class="mt-40">
        <Span class="col-span-8 col-start-3">
          <Grid :cols="3">
            <button
              v-for="item in project.media"
              :key="item.uuid"
              type="button"
              class="cursor-pointer relative"
              @click="selectedTeaserImage = item.uuid">
              <img
                :src="item.thumbnail_url"
                :alt="item.original_name"
                class="w-full h-auto aspect-square object-cover bg-white" />
              <span class="absolute top-10 left-10">
                <RadioIcon :variant="selectedTeaserImage === item.uuid ? 'checked' : 'unchecked'" class="w-12 text-white" />
              </span>
            </button>
          </Grid>
        </Span>
      </Grid>

    </Drawer>

	</template>

</template>
