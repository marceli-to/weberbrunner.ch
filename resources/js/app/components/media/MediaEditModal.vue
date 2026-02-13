<script setup>
import { ref, watch } from 'vue'
import Cross from '@/components/icons/Cross.vue'

const props = defineProps({
	media: { type: Object, default: null },
})

const emit = defineEmits(['close', 'save'])

const form = ref({
	alt: '',
	caption: '',
})

watch(() => props.media, (val) => {
	if (val) {
		form.value.alt = val.alt || ''
		form.value.caption = val.caption || ''
	}
}, { immediate: true })

function handleSave() {
	emit('save', {
		uuid: props.media.uuid,
		data: { ...form.value },
	})
}
</script>

<template>
	<div v-if="media" class="fixed inset-0 z-50 flex items-center justify-center">
		<div class="absolute inset-0 bg-black/50" @click="emit('close')"></div>
		<div class="relative bg-white w-full max-w-400 p-20">
			<button
				type="button"
				class="absolute top-12 right-12 w-20 h-20 flex items-center justify-center text-black"
				@click="emit('close')"
			>
				<Cross class="w-10 h-10" />
			</button>

			<h3 class="text-sm font-semibold text-black mb-16">Bild bearbeiten</h3>

			<div class="flex gap-16 mb-16">
				<img
					:src="media.thumbnail_url"
					:alt="media.alt || ''"
					class="w-80 h-80 object-cover border border-silver flex-none"
				/>
				<div class="text-xs text-gray leading-relaxed">
					<div>{{ media.original_name }}</div>
					<div>{{ media.width }} &times; {{ media.height }} px</div>
				</div>
			</div>

			<div class="mb-12">
				<label class="block text-sm font-semibold text-black mb-4">Alt-Text</label>
				<input
					v-model="form.alt"
					type="text"
					class="w-full border border-silver px-8 py-8 text-sm text-black focus:outline-none focus:border-black"
				/>
			</div>

			<div class="mb-16">
				<label class="block text-sm font-semibold text-black mb-4">Bildunterschrift</label>
				<input
					v-model="form.caption"
					type="text"
					class="w-full border border-silver px-8 py-8 text-sm text-black focus:outline-none focus:border-black"
				/>
			</div>

			<div class="flex gap-12">
				<button
					type="button"
					class="bg-black text-white text-sm font-semibold px-16 py-8"
					@click="handleSave"
				>
					Speichern
				</button>
				<button
					type="button"
					class="border border-black text-black text-sm font-semibold px-16 py-8"
					@click="emit('close')"
				>
					Abbrechen
				</button>
			</div>
		</div>
	</div>
</template>
