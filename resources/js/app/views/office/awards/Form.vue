<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import awardsApi from '@/api/awards'
import sectionsApi from '@/api/sections'
import { useFormErrors } from '@/composables/useFormErrors'
import PageTitle from '@/components/ui/PageTitle.vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import Button from '@/components/ui/form/Button.vue'
import Editor from '@/components/ui/form/editor/Editor.vue'
import Arrow from '@/components/icons/Arrow.vue'

const route = useRoute()
const router = useRouter()
const { get, clear, submit } = useFormErrors()

const isEdit = computed(() => !!route.params.id)
const sectionTitle = ref('')
const sectionId = ref(null)
const form = ref({
	text: '',
})

onMounted(async () => {
	if (isEdit.value) {
		const { data } = await awardsApi.show(route.params.id)
		form.value.text = data.data.text || ''
		sectionTitle.value = data.data.section?.title || ''
		sectionId.value = data.data.section?.id || null
	} else if (route.query.section) {
		const { data } = await sectionsApi.show(route.query.section)
		sectionTitle.value = data.data.title || ''
		sectionId.value = data.data.id || null
	}
})

async function handleSubmit() {
	let ok
	if (isEdit.value) {
		ok = await submit(() => awardsApi.update(route.params.id, { text: form.value.text }))
	} else {
		ok = await submit(() => awardsApi.store({ text: form.value.text, section_id: sectionId.value }))
	}
	if (ok) {
		router.push({ name: 'office.awards' })
	}
}

function goBack() {
	router.push({ name: 'office.awards' })
}
</script>

<template>

	<!-- Header -->
	<Grid class="mb-40">
		<Span class="col-span-1 flex items-center justify-center">
			<button @click="goBack">
				<Arrow variant="left" class="w-25 cursor-pointer" />
			</button>
		</Span>
		<Span class="col-span-8">
			<PageTitle>Auszeichnungen / {{ sectionTitle }}</PageTitle>
		</Span>
	</Grid>

	<!-- Editor -->
	<Grid>
		<Span class="col-span-8 col-start-2">
			<Editor v-model="form.text" @focus="clear('text')" />
			<p v-if="get('text')" class="text-sm text-red mt-4">{{ get('text') }}</p>
		</Span>
	</Grid>

	<!-- Bottom bar -->
	<div class="fixed bottom-0 left-0 right-0 bg-navy z-50">
		<Grid>
			<Span class="col-span-8 col-start-2 flex gap-20 py-16">
				<Button @click="handleSubmit" class="flex-1 justify-center">Speichern</Button>
				<Button variant="secondary" @click="goBack" class="flex-1 justify-center">Abbrechen</Button>
			</Span>
		</Grid>
	</div>

</template>
