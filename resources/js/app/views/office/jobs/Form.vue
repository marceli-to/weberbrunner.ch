<script setup>
import { ref, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import jobsApi from '@/api/jobs'
import locationsApi from '@/api/locations'
import { usePageLoader } from '@/composables/usePageLoader'
import { useFormErrors } from '@/composables/useFormErrors'
import PageTitle from '@/components/ui/PageTitle.vue'
import FormContainer from '@/components/ui/form/FormContainer.vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import Editor from '@/components/ui/form/editor/Editor.vue'
import Input from '@/components/ui/form/Input.vue'
import ActionBar from '@/components/ui/form/ActionBar.vue'
import Arrow from '@/components/icons/Arrow.vue'

const route = useRoute()
const router = useRouter()
const { load } = usePageLoader()
const { get, clear, submit } = useFormErrors({ toast: true })

const isEdit = computed(() => !!route.params.id)
const locationTitle = ref('')
const locationId = ref(null)
const form = ref({
	title: '',
	description: '',
	contact_email: '',
})

load(async () => {
	if (isEdit.value) {
		const { data } = await jobsApi.show(route.params.id)
		form.value.title = data.data.title || ''
		form.value.description = data.data.description || ''
		form.value.contact_email = data.data.contact_email || ''
		locationTitle.value = data.data.location?.title || ''
		locationId.value = data.data.location?.id || null
	} else if (route.query.location) {
		const { data } = await locationsApi.show(route.query.location)
		locationTitle.value = data.data.title || ''
		locationId.value = data.data.id || null
	}
})

async function handleSubmit() {
	const payload = {
		title: form.value.title,
		description: form.value.description,
		contact_email: form.value.contact_email || null,
	}
	let ok
	if (isEdit.value) {
		ok = await submit(() => jobsApi.update(route.params.id, payload))
	} else {
		ok = await submit(() => jobsApi.store({ ...payload, location_id: locationId.value }))
	}
	if (ok) {
		router.push({ name: 'office.jobs' })
	}
}

function goBack() {
	router.push({ name: 'office.jobs' })
}
</script>

<template>
	<FormContainer @submit="handleSubmit">

		<!-- Header -->
		<Grid class="mb-40">
			<Span class="col-span-1 flex items-center justify-center">
				<button type="button" @click="goBack">
					<Arrow variant="left" class="w-25 cursor-pointer" />
				</button>
			</Span>
			<Span class="col-span-8">
				<PageTitle>Jobs / {{ locationTitle }}</PageTitle>
			</Span>
		</Grid>

		<!-- Fields -->
		<Grid>

			<Span class="col-span-8 col-start-2">
				<Input v-model="form.title" placeholder="Titel" :error="get('title')" @focus="clear('title')" />
			</Span>

			<Span class="col-span-8 col-start-2">
				<Editor v-model="form.description" :error="get('description')" @focus="clear('description')" />
			</Span>

			<Span class="col-span-8 col-start-2">
				<Input v-model="form.contact_email" type="email" placeholder="Kontakt E-Mail" :error="get('contact_email')" @focus="clear('contact_email')" />
			</Span>

		</Grid>

		<!-- Bottom bar -->
		<ActionBar @cancel="goBack" />

	</FormContainer>
</template>
