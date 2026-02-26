<script setup>
import { ref, watch, nextTick } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import sectionsApi from '@/api/sections'
import { usePageLoader } from '@/composables/usePageLoader'
import { useFormErrors } from '@/composables/useFormErrors'
import PageTitle from '@/components/ui/PageTitle.vue'
import FormContainer from '@/components/ui/form/FormContainer.vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import Editor from '@/components/ui/form/editor/Editor.vue'
import ActionBar from '@/components/ui/form/ActionBar.vue'
import BackButton from '@/components/ui/BackButton.vue'

const props = defineProps({
	api: Object,
	pageTitle: String,
	backRoute: String,
	extraFields: {
		type: Object,
		default: () => ({}),
	},
	buildPayload: {
		type: Function,
		default: null,
	},
})

const route = useRoute()
const router = useRouter()
const { load } = usePageLoader()
const { get, clear, submit } = useFormErrors({ toast: true })

const isEdit = ref(!!route.params.id)
const sectionTitle = ref('')
const sectionId = ref(null)
const form = ref({
	text: '',
	...props.extraFields,
})
const dirty = ref(false)

function populateExtra(data) {
	for (const key of Object.keys(props.extraFields)) {
		form.value[key] = data[key] || ''
	}
}

load(async () => {
	if (isEdit.value) {
		const { data } = await props.api.show(route.params.id)
		form.value.text = data.data.text || ''
		sectionTitle.value = data.data.section?.title || ''
		sectionId.value = data.data.section?.id || null
		populateExtra(data.data)
	} else if (route.query.section) {
		const { data } = await sectionsApi.show(route.query.section)
		sectionTitle.value = data.data.title || ''
		sectionId.value = data.data.id || null
	}
	await nextTick()
	watch(form, () => { dirty.value = true }, { deep: true })
})

async function handleSubmit() {
	const payload = props.buildPayload
		? props.buildPayload(form.value)
		: { text: form.value.text }
	let ok
	if (isEdit.value) {
		ok = await submit(() => props.api.update(route.params.id, { ...payload, section_id: sectionId.value }))
	} else {
		ok = await submit(() => props.api.store({ ...payload, section_id: sectionId.value }))
	}
	if (ok) {
		router.push({ name: props.backRoute })
	}
}

function goBack() {
	router.push({ name: props.backRoute })
}

</script>

<template>
	<FormContainer @submit="handleSubmit">

		<!-- Header -->
		<Grid class="mb-40">
			<Span class="col-span-1 flex items-center justify-center">
				<BackButton @click="goBack" />
			</Span>
			<Span class="col-span-8">
				<PageTitle>{{ pageTitle }} / {{ sectionTitle }}</PageTitle>
			</Span>
		</Grid>

		<!-- Fields -->
		<Grid>
			<Span class="col-span-8 col-start-2">
				<Editor v-model="form.text" :error="get('text')" @focus="clear('text')" />
			</Span>

			<slot :form="form" :get="get" :clear="clear" />
		</Grid>

		<!-- Bottom bar -->
		<ActionBar v-show="dirty" @cancel="goBack" />

	</FormContainer>
</template>
