<script setup>
	import { ref, computed } from 'vue'
	import draggable from 'vuedraggable'
	import { usePublication } from '@/composables/usePublication'
	import { useCollapsed } from '@/composables/useCollapsed'
	import { useMediaStore } from '@/stores/media'
	import { useConfirm } from '@/composables/useConfirm'
	import { useToast } from '@/composables/useToast'
	import publicationsApi from '@/api/publications'
	import mediaApi from '@/api/media'
	import PublicationLayout from '@/views/office/publications/components/Layout.vue'
	import Grid from '@/components/ui/grid/Grid.vue'
	import Span from '@/components/ui/grid/Span.vue'
	import CollapsibleHeader from '@/components/ui/CollapsibleHeader.vue'
	import MediaCard from '@/components/media/MediaCard.vue'
	import MediaUploader from '@/components/media/MediaUploader.vue'
	import MediaEditModal from '@/components/media/MediaEditModal.vue'
	import AttributeList from '@/views/office/publications/components/AttributeList.vue'
	import Blocks from '@/views/office/publications/components/Blocks.vue'

	const mediaStore = useMediaStore()
	const { confirm } = useConfirm()
	const toast = useToast()

	const { publication, fetch } = usePublication((data) => {
		mediaStore.setItems((data.media || []).filter(m => m.is_image))
	})

	const file = computed(() => publication.value?.media?.find(m => !m.is_image) || null)
	const { collapsed, toggle } = useCollapsed('publication-show')

	const editingMedia = ref(null)

	const dragItems = computed({
		get: () => mediaStore.items,
		set: (value) => mediaStore.reorder(value),
	})

	async function onUploaded(media) {
		await publicationsApi.attachMedia(publication.value.uuid, [media])
		await fetch()
	}

	async function onDelete(item) {
		const ok = await confirm({
			message: 'Möchtest Du dieses Bild wirklich löschen?',
			confirmLabel: 'Löschen',
			variant: 'danger',
		})
		if (!ok) return
		await mediaStore.deleteItem(item.uuid)
	}

	async function onFileUploaded(media) {
		await publicationsApi.attachMedia(publication.value.uuid, [media])
		await fetch()
	}

	async function onFileDelete() {
		if (!file.value) return
		const ok = await confirm({
			message: 'Möchtest Du diese Datei wirklich löschen?',
			confirmLabel: 'Löschen',
			variant: 'danger',
		})
		if (!ok) return
		await mediaApi.destroy(file.value.uuid)
		await fetch()
	}

	async function onEditSave({ uuid, data }) {
		await mediaStore.updateItem(uuid, data)
		editingMedia.value = null
		toast.success('Bild gespeichert')
	}
</script>

<template>
	<PublicationLayout :publication="publication">

		<template v-if="publication">

			<Grid>

				<!-- Slider -->
				<Span class="col-span-8 col-start-2">

					<CollapsibleHeader title="Slider" :collapsed="collapsed.has('images')" @toggle="toggle('images')" />
					<div v-show="!collapsed.has('images')" class="mt-20">
						
						<draggable
							v-if="mediaStore.items.length"
							v-model="dragItems"
							item-key="uuid"
							handle=".drag-handle"
							class="grid grid-cols-2 lg:grid-cols-4 gap-20"
							ghost-class="opacity-30"
							animation="150"
						>
							<template #item="{ element }">
								<MediaCard
									:item="element"
									:draggable="true"
									:deletable="true"
									:editable="true"
									:show-filename="true"
									variant="dark"
									@delete="onDelete"
									@edit="editingMedia = $event" />
							</template>
						</draggable>

						<div :class="{ 'mt-20': mediaStore.items.length }">
							<MediaUploader @uploaded="onUploaded" />
						</div>
					</div>
				</Span>

				<!-- Attribute -->
				<Span class="col-span-8 col-start-2">
					<CollapsibleHeader title="Attribute" :collapsed="collapsed.has('attributes')" @toggle="toggle('attributes')" />
					<div v-show="!collapsed.has('attributes')" class="mt-20">
						<AttributeList :publication="publication" @updated="fetch" />
					</div>
				</Span>

				<!-- File upload -->
				<Span class="col-span-8 col-start-2">
					<CollapsibleHeader title="Download" :collapsed="collapsed.has('file')" @toggle="toggle('file')" />
					<div v-show="!collapsed.has('file')" class="mt-20">
						<div v-if="file" class="grid grid-cols-2 lg:grid-cols-4">
							<MediaCard :item="file" :deletable="true" :show-filename="true" variant="dark" @delete="onFileDelete" />
						</div>
						<MediaUploader v-else :allowed-file-types="['.pdf', '.doc', '.docx', '.xls', '.xlsx', '.zip']" @uploaded="onFileUploaded" />
					</div>
				</Span>

			</Grid>

			<!-- Dynamic blocks + block type picker -->
			<Blocks :publication="publication" @updated="fetch" />

		</template>

		<MediaEditModal
			:media="editingMedia"
			@save="onEditSave"
			@close="editingMedia = null" />

	</PublicationLayout>
</template>
