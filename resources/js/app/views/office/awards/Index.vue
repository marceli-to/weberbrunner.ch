<script setup>
import { ref, computed, onMounted } from 'vue'
import awardsApi from '@/api/awards'
import sectionsApi from '@/api/sections'
import { useFormErrors } from '@/composables/useFormErrors'
import { useLightbox } from '@/composables/useLightbox'
import PageTitle from '@/components/ui/PageTitle.vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import Button from '@/components/ui/form/Button.vue'
import Input from '@/components/ui/form/Input.vue'
import Burger from '@/components/icons/Burger.vue'
import Chevron from '@/components/icons/Chevron.vue'
import Cross from '@/components/icons/Cross.vue'
import Eye from '@/components/icons/Eye.vue'
import Pencil from '@/components/icons/Pencil.vue'
import Plus from '@/components/icons/Plus.vue'
import Lightbox from '@/components/ui/lightbox/Lightbox.vue'

const awards = ref([])
const title = ref('')
const { get, clear, submit } = useFormErrors()
const { show, open, close } = useLightbox(() => {
	title.value = ''
	clear()
})

const grouped = computed(() => {
	const sections = []
	const map = new Map()
	for (const award of awards.value) {
		const section = award.section
		if (!map.has(section.uuid)) {
			const group = { section, awards: [] }
			map.set(section.uuid, group)
			sections.push(group)
		}
		map.get(section.uuid).awards.push(award)
	}
	return sections
})

async function fetchAwards() {
	const { data } = await awardsApi.index()
	awards.value = data.data
}

async function store() {
	const ok = await submit(() => sectionsApi.store({ title: title.value, type: 'award' }))
	if (ok) {
		close()
		await fetchAwards()
	}
}

function stripTags(html) {
	const div = document.createElement('div')
	div.innerHTML = html
	return div.textContent
}

onMounted(fetchAwards)
</script>

<template>

	<Grid class="mb-40">

		<Span class="col-span-8 col-start-2">
			<PageTitle>Auszeichnungen</PageTitle>
		</Span>

		<Span class="col-span-8 col-start-2">
			<Button
        @click="open"
        class="px-20">
				<template #icon-right>
					<Plus class="w-10 h-10" />
				</template>
				Neue Kategorie
			</Button>
		</Span>
  </Grid>

  <Grid>

		<Span v-for="group in grouped" :key="group.section.uuid" class="col-span-10">
      <Grid :cols="10">

        <Span class="col-span-1 flex items-center justify-end">
          <Burger class="w-18 h-10 cursor-grab" />
        </Span>

        <Span class="col-span-8">

          <div class="bg-white text-lg font-semibold min-h-50 flex justify-between items-center px-20">
            <span>
              {{ group.section.title }}
            </span>
            <Chevron variant="up" size="lg" class="w-20" />
          </div>

        </Span>

        <Span class="col-span-1 flex items-center justify-start">
          <Cross class="w-10 cursor-pointer" />
        </Span>

        <Span class="col-span-8 col-start-2">
          <div class="flex flex-col gap-10">
            <div v-for="award in group.awards" :key="award.uuid" class="bg-white font-semibold min-h-30 border border-black flex justify-between items-center px-20">
              <span>
                {{ stripTags(award.text) }}
              </span>
              <span class="flex gap-x-20">
                <Pencil class="w-14 cursor-pointer" />
                <Eye class="w-14 cursor-pointer" />
              </span>
            </div>
          </div>
        </Span>

      </Grid>
		</Span>

	</Grid>

	<Lightbox :open="show" title="Neue Kategorie" @close="close" :closeable="false">
		<form @submit.prevent="store">
			<Input v-model="title" :error="get('title')" placeholder="Bezeichnung" class="form-input form-input--lg" @focus="clear('title')" />
			<div class="flex gap-20 mt-24">
				<Button @click="store" class="flex justify-center">Speichern</Button>
				<Button @click="close" class="flex justify-center">Abbrechen</Button>
			</div>
		</form>
	</Lightbox>

</template>
