<script setup>
import { ref, onMounted } from 'vue'
import { useFormErrors } from '@/composables/useFormErrors'
import { useLightbox } from '@/composables/useLightbox'
import projectsApi from '@/api/projects'
import locationsApi from '@/api/locations'
import Lightbox from '@/components/ui/lightbox/Lightbox.vue'
import Button from '@/components/ui/form/Button.vue'
import Input from '@/components/ui/form/Input.vue'
import Radio from '@/components/ui/form/Radio.vue'

const emit = defineEmits(['created'])

const priority = ref('')
const number = ref('')
const title = ref('')
const city = ref('')
const locationId = ref('')
const locations = ref([])

const priorities = ['A', 'B', 'C']

const { get, clear, submit } = useFormErrors()
const { show, open: openLightbox, close } = useLightbox(() => {
	clear()
	priority.value = ''
	number.value = ''
	title.value = ''
	city.value = ''
	locationId.value = ''
})

onMounted(async () => {
	const { data } = await locationsApi.index()
	locations.value = data.data
})

function open() {
	openLightbox()
}

async function save() {
	let created = null
	const ok = await submit(async () => {
		const response = await projectsApi.store({
			priority: priority.value || null,
			number: number.value === '' ? null : Number(number.value),
			title: title.value,
			city: city.value || null,
			location_id: locationId.value || null,
		})
		created = response.data.data
		return response
	})
	if (ok) {
		close()
		emit('created', created)
	}
}

defineExpose({ open })
</script>

<template>
	<Lightbox :open="show" title="Projekt erfassen" @close="close">
		<form @submit.prevent="save" class="px-20">
			<Input v-model="number" :error="get('number')" placeholder="Nr." class="form-input form-input--lg mt-10" @focus="clear('number')" />
			<Input v-model="title" :error="get('title')" placeholder="Projektname" class="form-input form-input--lg mt-10" @focus="clear('title')" />
			<Input v-model="city" :error="get('city')" placeholder="Ort" class="form-input form-input--lg mt-10" @focus="clear('city')" />

      <div class="flex gap-x-40 mt-20">
        <div :class="['flex gap-20', { 'has-error': get('priority') }]" @click="clear('priority')">
          <label class="text-sm font-semibold">Priorität</label>
          <Radio
            v-for="p in priorities"
            :key="p"
            v-model="priority"
            :value="p"
            :label="p"
            name="priority" />
        </div>
        <div :class="['flex gap-20', { 'has-error': get('location_id') }]" @click="clear('location_id')">
          <label class="text-sm font-semibold">Standort</label>
          <Radio
            v-for="loc in locations"
            :key="loc.id"
            v-model="locationId"
            :value="loc.id"
            :label="loc.title"
            name="location" />
        </div>
      </div>

			<div class="flex gap-20 mt-20">
				<Button type="submit" class="flex justify-center">Erstellen</Button>
				<Button @click="close" class="flex justify-center">Abbrechen</Button>
			</div>

		</form>
	</Lightbox>
</template>
