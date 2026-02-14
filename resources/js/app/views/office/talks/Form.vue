<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import talksApi from '@/api/talks'
import sectionsApi from '@/api/sections'
import { useFormErrors } from '@/composables/useFormErrors'
import PageTitle from '@/components/ui/PageTitle.vue'
import FormContainer from '@/components/ui/form/FormContainer.vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import Editor from '@/components/ui/form/editor/Editor.vue'
import ActionBar from '@/components/ui/form/ActionBar.vue'
import Arrow from '@/components/icons/Arrow.vue'

const route = useRoute()
const router = useRouter()
const { get, clear, submit } = useFormErrors({ toast: true })

const isEdit = computed(() => !!route.params.id)
const sectionTitle = ref('')
const sectionId = ref(null)
const form = ref({
	text: '',
})

onMounted(async () => {
	if (isEdit.value) {
		const { data } = await talksApi.show(route.params.id)
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
		ok = await submit(() => talksApi.update(route.params.id, { text: form.value.text }))
	} else {
		ok = await submit(() => talksApi.store({ text: form.value.text, section_id: sectionId.value }))
	}
	if (ok) {
		router.push({ name: 'office.talks' })
	}
}

function goBack() {
	router.push({ name: 'office.talks' })
}
</script>

<template>
	<FormContainer @submit="handleSubmit">

		<!-- Header -->
		<Grid class="mb-40">
			<Span class="col-span-1 flex items-center justify-center">
				<button @click="goBack">
					<Arrow variant="left" class="w-25 cursor-pointer" />
				</button>
			</Span>
			<Span class="col-span-8">
				<PageTitle>Vorträge / {{ sectionTitle }}</PageTitle>
			</Span>
		</Grid>

		<!-- Editor -->
		<Grid>
			<Span class="col-span-8 col-start-2">
				<Editor v-model="form.text" :error="get('text')" @focus="clear('text')" />
			</Span>
		</Grid>

		<!-- Bottom bar -->
		<ActionBar @cancel="goBack" />

	</FormContainer>
</template>
