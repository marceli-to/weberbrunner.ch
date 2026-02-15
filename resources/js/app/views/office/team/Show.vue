<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import teamApi from '@/api/team'
import PageTitle from '@/components/ui/PageTitle.vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import Arrow from '@/components/icons/Arrow.vue'

const route = useRoute()
const router = useRouter()
const member = ref(null)

onMounted(async () => {
	const { data } = await teamApi.show(route.params.id)
	member.value = data.data
})

function goBack() {
	router.push({ name: 'office.team' })
}
</script>

<template>
  <template v-if="member">

    <!-- Header -->
    <Grid class="mb-20">

      <Span class="col-span-1 flex items-center justify-center">
        <button type="button" @click="goBack">
          <Arrow variant="left" class="w-25 cursor-pointer" />
        </button>
      </Span>
      <Span class="col-span-8">
        <PageTitle>{{ member.firstname }} {{ member.name }}</PageTitle>
      </Span>

    </Grid>

    <!-- Details -->
    <Grid>
      <Span class="col-span-2 col-start-2">
        [Image]
      </Span>
      <Span class="col-span-6 flex flex-col gap-20">

        <div class="bg-white p-20">
          <p>{{ member.firstname }} {{ member.name }}</p>
          <p>{{ member.title }}</p>
          <p>{{ member.location?.title }}</p>
        </div>

        <div class="bg-white p-20">
          <p>{{ member.firstname }} {{ member.name }}</p>
          <p>{{ member.title }}</p>
          <p>{{ member.location?.title }}</p>
        </div>

      </Span>
    </Grid>

  </template>
</template>
