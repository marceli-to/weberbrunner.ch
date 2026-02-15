<script setup>
import { computed } from 'vue'
import teamApi from '@/api/team'
import mediaApi from '@/api/media'
import MediaUploader from '@/components/media/MediaUploader.vue'
import Cross from '@/components/icons/Cross.vue'
import { useConfirm } from '@/composables/useConfirm'

const props = defineProps({
	member: { type: Object, required: true },
})

const emit = defineEmits(['updated'])
const { confirm } = useConfirm()
const image = computed(() => props.member.media?.[0] || null)

async function onUploaded(media) {
	await teamApi.attachMedia(props.member.uuid, [{
		uuid: media.uuid,
		file: media.file,
		original_name: media.original_name,
		mime_type: media.mime_type,
		size: media.size,
		width: media.width,
		height: media.height,
	}])
	emit('updated')
}

async function onDelete() {
	if (!image.value) return
	const ok = await confirm({
		message: 'Möchtest Du dieses Bild wirklich löschen?',
		confirmLabel: 'Löschen',
		variant: 'danger',
	})
	if (!ok) return
	await mediaApi.destroy(image.value.uuid)
	emit('updated')
}
</script>

<template>

	<template v-if="image">
		<div class="bg-white border border-silver">
			<div class="relative">
				<button
					type="button"
					class="absolute top-20 right-20 cursor-pointer"
					@click="onDelete"
				>
					<Cross class="w-12 h-auto" />
				</button>
				<div class="py-60">
					<img
						:src="image.preview_url"
						:alt="image.alt || ''"
						class="block w-full max-w-[60%] mx-auto"
					/>
				</div>
			</div>
			<div class="text-center py-3 text-sm border-t border-silver">
				{{ image.original_name }}
			</div>
		</div>
	</template>

	<template v-else>
		<MediaUploader @uploaded="onUploaded" />
	</template>

</template>
