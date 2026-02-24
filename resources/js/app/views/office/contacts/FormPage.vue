<script setup>
import { ref, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import contactsApi from '@/api/contacts'
import locationsApi from '@/api/locations'
import mediaApi from '@/api/media'
import { usePageLoader } from '@/composables/usePageLoader'
import { useFormErrors } from '@/composables/useFormErrors'
import { useConfirm } from '@/composables/useConfirm'
import PageTitle from '@/components/ui/PageTitle.vue'
import FormContainer from '@/components/ui/form/FormContainer.vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import Input from '@/components/ui/form/Input.vue'
import Textarea from '@/components/ui/form/Textarea.vue'
import ActionBar from '@/components/ui/form/ActionBar.vue'
import BackButton from '@/components/ui/BackButton.vue'
import MediaUploader from '@/components/media/MediaUploader.vue'
import MediaCard from '@/components/media/MediaCard.vue'

const route = useRoute()
const router = useRouter()
const { load } = usePageLoader()
const { get, clear, submit } = useFormErrors({ toast: true })
const { confirm } = useConfirm()

const isEdit = computed(() => !!route.params.id)
const locationTitle = ref('')
const locationId = ref(null)
const image = ref(null)
const form = ref({
	company_name: '',
	address: '',
	phone: '',
	email: '',
	maps_url: '',
})

async function fetch() {
	const { data } = await contactsApi.show(route.params.id)
	form.value.company_name = data.data.company_name || ''
	form.value.address = data.data.address || ''
	form.value.phone = data.data.phone || ''
	form.value.email = data.data.email || ''
	form.value.maps_url = data.data.maps_url || ''
	locationTitle.value = data.data.location?.title || ''
	locationId.value = data.data.location?.id || null
	image.value = data.data.media?.[0] || null
}

load(async () => {
	if (isEdit.value) {
		await fetch()
	} else if (route.query.location) {
		const { data } = await locationsApi.show(route.query.location)
		locationTitle.value = data.data.title || ''
		locationId.value = data.data.id || null
	}
})

async function handleSubmit() {
	const payload = {
		company_name: form.value.company_name,
		address: form.value.address,
		phone: form.value.phone || null,
		email: form.value.email || null,
		maps_url: form.value.maps_url || null,
	}
	let ok
	if (isEdit.value) {
		ok = await submit(() => contactsApi.update(route.params.id, payload))
	} else {
		ok = await submit(() => contactsApi.store({ ...payload, location_id: locationId.value }))
	}
	if (ok) {
		router.push({ name: 'office.contacts' })
	}
}

async function onUploaded(media) {
	await contactsApi.attachMedia(route.params.id, [{
		uuid: media.uuid,
		file: media.file,
		original_name: media.original_name,
		mime_type: media.mime_type,
		size: media.size,
		width: media.width,
		height: media.height,
	}])
	await fetch()
}

async function onDeleteImage() {
	if (!image.value) return
	const ok = await confirm({
		message: 'Möchtest Du dieses Bild wirklich löschen?',
		confirmLabel: 'Löschen',
		variant: 'danger',
	})
	if (!ok) return
	await mediaApi.destroy(image.value.uuid)
	await fetch()
}

function goBack() {
	router.push({ name: 'office.contacts' })
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
				<PageTitle>Kontakt / {{ locationTitle }}</PageTitle>
			</Span>
		</Grid>

		<!-- Fields -->
		<Grid>

			<Span class="col-span-8 col-start-2">
 				<Textarea v-model="form.company_name" placeholder="Firmenname" rows="3" :error="get('company_name')" @focus="clear('company_name')" />
			</Span>

			<Span class="col-span-8 col-start-2">
				<Textarea v-model="form.address" placeholder="Adresse" rows="3" :error="get('address')" @focus="clear('address')" />
			</Span>

			<Span class="col-span-8 col-start-2">
				<Input v-model="form.phone" placeholder="Telefon" :error="get('phone')" @focus="clear('phone')" />
			</Span>

			<Span class="col-span-8 col-start-2">
				<Input v-model="form.email" type="email" placeholder="E-Mail" :error="get('email')" @focus="clear('email')" />
			</Span>

			<Span class="col-span-8 col-start-2">
				<Input v-model="form.maps_url" placeholder="Google Maps URL" :error="get('maps_url')" @focus="clear('maps_url')" />
			</Span>

			<!-- Image upload (only in edit mode) -->
			<Span v-if="isEdit" class="col-span-2 col-start-2">
				<template v-if="image">
					<MediaCard
						:item="image"
						:deletable="true"
						:show-filename="true"
						:compact="true"
						@delete="onDeleteImage"
					/>
				</template>
				<template v-else>
					<MediaUploader @uploaded="onUploaded" />
				</template>
			</Span>

		</Grid>

		<!-- Bottom bar -->
		<ActionBar @cancel="goBack" />

	</FormContainer>
</template>
