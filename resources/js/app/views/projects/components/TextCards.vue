<script setup>
import Card from '@/components/ui/Card.vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import PencilCircle from '@/components/icons/PencilCircle.vue'
import { useCan } from '@/composables/useCan'

const { canUpdate } = useCan()

defineProps({
  project: { type: Object, required: true },
})
</script>

<template>
  <Grid :cols="8" class="items-stretch">
    <Span class="col-span-4 h-full">
      <Card header class="h-full flex flex-col">
        <div class="font-semibold text-md min-h-50 flex items-center justify-between border-b-thin w-full">
          <span>Erläuterung Projekt</span>
          <template v-if="canUpdate">
            <RouterLink :to="{ name: 'projects.text.edit', params: { id: project.uuid } }" class="cursor-pointer">
              <PencilCircle class="w-25" />
            </RouterLink>
          </template>
        </div>
        <div class="text-md pt-5 pb-20 flex-1 border-b-thin">
          <span class="line-clamp-12" v-html="project.description"></span>
        </div>
      </Card>
    </Span>
    <Span class="col-span-4 h-full">
      <Card header class="h-full flex flex-col">
        <div class="font-semibold text-md min-h-50 flex items-center justify-between border-b-thin w-full">
          <span>Projektbeschrieb</span>
          <template v-if="canUpdate">
            <RouterLink :to="{ name: 'projects.text.edit', params: { id: project.uuid } }" class="cursor-pointer">
              <PencilCircle class="w-25" />
            </RouterLink>
          </template>
        </div>
        <div class="text-md pt-5 pb-20 flex-1 border-b-thin">
          <span class="line-clamp-12" v-html="project.short_description"></span>
        </div>
      </Card>
    </Span>
  </Grid>
</template>
