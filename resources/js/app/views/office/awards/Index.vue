<script setup>
import { ref } from 'vue'
import sectionsApi from '@/api/sections'
import { useFormErrors } from '@/composables/useFormErrors'
import { useLightbox } from '@/composables/useLightbox'
import PageTitle from '@/components/ui/PageTitle.vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import Button from '@/components/ui/form/Button.vue'
import Input from '@/components/ui/form/Input.vue'
import Plus from '@/components/icons/Plus.vue'
import Lightbox from '@/components/ui/lightbox/Lightbox.vue'

const title = ref('')
const { get, clear, submit } = useFormErrors()
const { show, open, close } = useLightbox(() => {
	title.value = ''
	clear()
})

async function store() {
	const ok = await submit(() => sectionsApi.store({ title: title.value, type: 'award' }))
	if (ok) close()
}
</script>

<template>

	<Grid class="mb-20">

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

	<Lightbox :open="show" title="Neue Kategorie" @close="close">
		<form @submit.prevent="store">
			<Input v-model="title" :error="get('title')" placeholder="Bezeichnung" class="form-input form-input--lg" @focus="clear('title')" />
			<div class="flex gap-20 mt-24">
				<Button @click="store" class="flex justify-center">Speichern</Button>
				<Button @click="close" class="flex justify-center">Abbrechen</Button>
			</div>
		</form>
	</Lightbox>

</template>
