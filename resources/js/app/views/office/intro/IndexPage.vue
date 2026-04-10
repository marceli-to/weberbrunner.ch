<script setup>
import { ref, computed } from 'vue'
import landingTextApi from '@/api/landingText'
import { usePageLoader } from '@/composables/usePageLoader'
import { useFormErrors } from '@/composables/useFormErrors'
import { useToast } from '@/composables/useToast'
import PageTitle from '@/components/ui/PageTitle.vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import Card from '@/components/ui/Card.vue'
import Button from '@/components/ui/form/Button.vue'
import Input from '@/components/ui/form/Input.vue'
import Textarea from '@/components/ui/form/Textarea.vue'

const { load } = usePageLoader()
const { submit } = useFormErrors()
const toast = useToast()

const form = ref({ title: '', text: '' })
const original = ref({ title: '', text: '' })

const dirty = computed(() =>
	form.value.title !== original.value.title || form.value.text !== original.value.text
)

async function fetch() {
	const { data } = await landingTextApi.show('office')
	form.value = { title: data.data.title ?? '', text: data.data.text ?? '' }
	original.value = { title: data.data.title ?? '', text: data.data.text ?? '' }
}

async function save() {
	const ok = await submit(() => landingTextApi.update('office', {
		title: form.value.title,
		text: form.value.text,
	}))
	if (!ok) return
	original.value = { ...form.value }
	toast.success('Gespeichert')
}

function cancel() {
	form.value = { ...original.value }
}

load(fetch)
</script>

<template>

	<Grid class="mb-40">
		<Span class="col-span-8 col-start-2">
			<PageTitle>Intro</PageTitle>
		</Span>
	</Grid>

	<Grid>
		<Span class="col-span-8 col-start-2">
			<Card>
				<form @submit.prevent="save">
					<Input v-model="form.title" class="mb-10" placeholder="Titel" />
					<Textarea v-model="form.text" placeholder="Text" :rows="8" />
					<div class="flex gap-20 mt-10">
						<Button type="submit" class="flex justify-center" :disabled="!dirty">Speichern</Button>
						<Button type="button" class="flex justify-center" :disabled="!dirty" @click="cancel">Abbrechen</Button>
					</div>
				</form>
			</Card>
		</Span>
	</Grid>

</template>
