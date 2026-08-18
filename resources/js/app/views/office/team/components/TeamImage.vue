<script setup>
import { computed } from 'vue'
import teamApi from '@/api/team'
import mediaApi from '@/api/media'
import MediaUploader from '@/components/media/MediaUploader.vue'
import MediaCard from '@/components/media/MediaCard.vue'
import { useConfirm } from '@/composables/useConfirm'
import { useCan } from '@/composables/useCan'

const props = defineProps({
	member: { type: Object, required: true },
})

const emit = defineEmits(['updated'])
const { confirm } = useConfirm()
const { canUpload, canDeletePortrait } = useCan()
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
		<MediaCard
			:item="image"
			:deletable="canDeletePortrait"
			:show-filename="true"
			:compact="true"
			@delete="onDelete"
		/>
	</template>

	<template v-else>
		<template v-if="canUpload">
			<MediaUploader @uploaded="onUploaded" />
		</template>
	</template>

</template>
