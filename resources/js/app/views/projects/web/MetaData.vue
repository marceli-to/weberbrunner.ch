<script setup>
import { useRoute, useRouter } from 'vue-router'
import { useProjectMeta } from '@/composables/useProjectMeta'
import { useCollapsed } from '@/composables/useCollapsed'
import PageTitle from '@/components/ui/PageTitle.vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import BackButton from '@/components/ui/BackButton.vue'
import WebNavBar from '@/views/projects/components/navbar/Web.vue'
import Card from '@/components/ui/Card.vue'
import CollapsibleHeader from '@/components/ui/CollapsibleHeader.vue'
import MediaCard from '@/components/media/MediaCard.vue'
import AddButton from '@/components/media/AddButton.vue'
import MediaPickerDrawer from '@/components/media/MediaPickerDrawer.vue'
import Textarea from '@/components/ui/form/Textarea.vue'
import Button from '@/components/ui/form/Button.vue'

const route = useRoute()
const router = useRouter()
const {
	project, ogImage, selectedOgImage, ogDrawerOpen,
	saveDescription, saveOgImage, removeOgImage,
} = useProjectMeta()
const { collapsed, toggle } = useCollapsed('project-meta')

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

    <!-- Meta Description -->
    <Grid class="mb-20">

      <Span class="col-span-8 col-start-2">
        <CollapsibleHeader
          :title="'Meta Description'"
          :collapsed="collapsed.has('meta')"
          @toggle="toggle('meta')" />
      </Span>

      <Span v-show="!collapsed.has('meta')" class="col-span-8 col-start-2">
        <Card>
          <form @submit.prevent="saveDescription">
            <Textarea v-model="project.meta_description" />
            <div class="flex gap-20 mt-10">
              <Button type="submit" class="flex justify-center">Speichern</Button>
            </div>
          </form>
        </Card>
      </Span>

    </Grid>

    <!-- Open Graph Image -->
    <Grid class="mb-20">

      <Span class="col-span-8 col-start-2">
        <CollapsibleHeader
          :title="'Open Graph Image'"
          :collapsed="collapsed.has('og')"
          @toggle="toggle('og')" />
      </Span>

      <Span v-show="!collapsed.has('og')" class="col-span-2 col-start-2">
        <div v-if="ogImage">
          <MediaCard :item="ogImage" deletable editable @delete="removeOgImage" @edit="ogDrawerOpen = true" />
        </div>
        <AddButton v-else @click="ogDrawerOpen = true" />
      </Span>

    </Grid>

    <MediaPickerDrawer
      :open="ogDrawerOpen"
      :items="project.media"
      v-model="selectedOgImage"
      @close="ogDrawerOpen = false"
      @submit="saveOgImage" />

	</template>

</template>
