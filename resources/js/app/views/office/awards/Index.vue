<script setup>
import { ref } from 'vue'
import sectionsApi from '@/api/sections'
import PageTitle from '@/components/ui/PageTitle.vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import Button from '@/components/ui/form/Button.vue'
import Input from '@/components/ui/form/Input.vue'
import Plus from '@/components/icons/Plus.vue'
import Lightbox from '@/components/ui/lightbox/Lightbox.vue'

const showLightbox = ref(false)
const title = ref('')

function openLightbox() {
	title.value = ''
	showLightbox.value = true
}

function closeLightbox() {
	showLightbox.value = false
}

async function store() {
	await sectionsApi.store({ title: title.value, type: 'award' })
	closeLightbox()
}
</script>

<template>

	<Grid class="mb-20">

		<Span class="col-span-8 col-start-2">
			<PageTitle>Auszeichnungen</PageTitle>
		</Span>

		<Span class="col-span-8 col-start-2">
			<Button
        @click="openLightbox"
        class="px-20">
				<template #icon-right>
					<Plus class="w-10 h-10" />
				</template>
				Neue Kategorie
			</Button>
		</Span>

	</Grid>

	<Lightbox :open="showLightbox" title="Neue Kategorie" @close="closeLightbox">
		<form @submit.prevent="store">
			<Input v-model="title" placeholder="Bezeichnung" class="form-input" />
			<div class="flex gap-20 mt-24">
				<Button @click="store" class="flex justify-center">Speichern</Button>
				<Button @click="closeLightbox" class="flex justify-center">Abbrechen</Button>
			</div>
		</form>
	</Lightbox>

</template>
