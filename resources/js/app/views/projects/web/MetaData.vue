<script setup>
import { ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useProjectMeta } from '@/composables/useProjectMeta'
import PageTitle from '@/components/ui/PageTitle.vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import BackButton from '@/components/ui/BackButton.vue'
import WebNavBar from '@/views/projects/components/navbar/Web.vue'
import Card from '@/components/ui/Card.vue'
import CollapsibleHeader from '@/components/ui/CollapsibleHeader.vue'
import Drawer from '@/components/ui/drawer/Drawer.vue'
import MediaCard from '@/components/media/MediaCard.vue'
import PlusCircle from '@/components/icons/PlusCircle.vue'
import Textarea from '@/components/ui/form/Textarea.vue'
import RadioIcon from '@/components/icons/Radio.vue'
import Button from '@/components/ui/form/Button.vue'

const route = useRoute()
const router = useRouter()
const {
	project, ogImage, selectedOgImage, ogDrawerOpen,
	saveDescription, saveOgImage, removeOgImage,
} = useProjectMeta()
const collapsedMeta = ref(false)
const collapsedOg = ref(false)
const ogDrawerView = ref('list')

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

    <!-- Content -->
    <Grid class="mb-20">

      <Span class="col-span-8 col-start-2">
        <CollapsibleHeader
          :title="'Meta Description'"
          :collapsed="collapsedMeta"
          @toggle="collapsedMeta = !collapsedMeta" />
      </Span>

      <Span v-show="!collapsedMeta" class="col-span-8 col-start-2">
        <Card>
          <form @submit.prevent="saveDescription">
            <Textarea v-model="project.meta_description" />
            <div class="flex gap-20 mt-24">
              <Button type="submit" class="flex justify-center">Speichern</Button>
            </div>
          </form>
        </Card>
      </Span>

    </Grid>

    <Grid class="mb-20">

      <Span class="col-span-8 col-start-2">
        <CollapsibleHeader
          :title="'Open Graph Image'"
          :collapsed="collapsedOg"
          @toggle="collapsedOg = !collapsedOg" />
      </Span>

      <Span v-show="!collapsedOg" class="col-span-2 col-start-2">
        <div v-if="ogImage">
          <MediaCard :item="ogImage" deletable editable @delete="removeOgImage" @edit="ogDrawerOpen = true" />
        </div>
        <button
          v-else
          type="button"
          class="border-thin border-silver bg-white flex justify-center p-10 w-full aspect-square cursor-pointer relative"
          @click="ogDrawerOpen = true">
          <span class="font-semibold block">Hinzufügen</span>
          <span class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
            <PlusCircle class="w-25" />
          </span>
        </button>
      </Span>

    </Grid>

    <Drawer :open="ogDrawerOpen" @close="ogDrawerOpen = false">

      <Grid :cols="6" class="mt-140">
        <Span class="col-span-4 col-start-2 flex flex-col gap-y-10">
          <Button :variant="ogDrawerView === 'list' ? 'primary' : 'ghost'" class="px-10" @click="ogDrawerView = 'list'">Text / Bilder</Button>
          <Button :variant="ogDrawerView === 'grid' ? 'primary' : 'ghost'" class="px-10" @click="ogDrawerView = 'grid'">Bilder</Button>
        </Span>
      </Grid>

      <!-- List view -->
      <Grid v-if="ogDrawerView === 'list'" :cols="6" class="mt-40">
        <Span class="col-span-4 col-start-2" v-for="item in project.media" :key="item.uuid">
          <Grid :cols="4">
            <Span class="col-span-3">
              <button type="button" class="flex items-start gap-x-10 border-t-thin border-t-white pt-10 cursor-pointer w-full text-left" @click="selectedOgImage = item.uuid">
                <RadioIcon
                  :variant="selectedOgImage === item.uuid ? 'checked' : 'unchecked'"
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
      <Grid v-if="ogDrawerView === 'grid'" :cols="12" class="mt-40">
        <Span class="col-span-8 col-start-3">
          <Grid :cols="3">
            <button
              v-for="item in project.media"
              :key="item.uuid"
              type="button"
              class="cursor-pointer relative"
              @click="selectedOgImage = item.uuid">
              <img
                :src="item.thumbnail_url"
                :alt="item.original_name"
                class="w-full h-auto aspect-square object-cover bg-white" />
              <span class="absolute top-10 left-10">
                <RadioIcon :variant="selectedOgImage === item.uuid ? 'checked' : 'unchecked'" class="w-12 text-white" />
              </span>
            </button>
          </Grid>
        </Span>
      </Grid>

      <Grid :cols="6" class="mt-40">
        <Span class="col-span-4 col-start-2 flex flex-col gap-y-10">
          <Button variant="ghost" class="px-10" @click="saveOgImage">Übernehmen</Button>
          <Button variant="ghost" class="px-10" @click="ogDrawerOpen = false">Abbrechen</Button>
        </Span>
      </Grid>

    </Drawer>


	</template>

</template>
