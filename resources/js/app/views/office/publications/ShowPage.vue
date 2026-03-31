<script setup>
	import { ref, computed } from 'vue'
	import draggable from 'vuedraggable'
	import { usePublication } from '@/composables/usePublication'
	import { useCollapsed } from '@/composables/useCollapsed'
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

	const { confirm } = useConfirm()
	const toast = useToast()

	const { publication, fetch } = usePublication()

	const file = computed(() => publication.value?.media?.find(m => !m.is_image) || null)
	const { collapsed, toggle } = useCollapsed('publication-show')

	const editingMedia = ref(null)

	const fixedSliderBlock = computed(() =>
		(publication.value?.blocks || []).find(b => b.type === 'fixed-slider')
	)

	const sliderImages = computed(() =>
		fixedSliderBlock.value?.media || []
	)

	async function ensureFixedSliderBlock() {
		if (fixedSliderBlock.value) return fixedSliderBlock.value
		const { data } = await publicationsApi.blocks.store(publication.value.uuid, { type: 'fixed-slider' })
		return data.data
	}

	async function onUploaded(media) {
		const block = await ensureFixedSliderBlock()
		const { data } = await mediaApi.persist(media)
		await publicationsApi.blocks.selectMedia(publication.value.uuid, block.uuid, [data.data.uuid])
		await fetch()
	}

	async function onDelete(item) {
		const ok = await confirm({
			message: 'Möchtest Du dieses Bild wirklich löschen?',
			confirmLabel: 'Löschen',
			variant: 'danger',
		})
		if (!ok) return
		await publicationsApi.blocks.detachMedia(publication.value.uuid, fixedSliderBlock.value.uuid, item.uuid)
		await fetch()
	}

	async function reorderSliderImages() {
		const items = sliderImages.value.map((m, index) => ({
			uuid: m.uuid,
			sort_order: index,
		}))
		await mediaApi.reorder(items)
		await fetch()
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
		await mediaApi.update(uuid, data)
		editingMedia.value = null
		toast.success('Bild gespeichert')
		await fetch()
	}
</script>

<template>
	<PublicationLayout :publication="publication">

		<template v-if="publication">

			<Grid :class="publication.blocks?.length ? 'mb-20' : ''">

				<!-- Slider -->
				<Span class="col-span-8 col-start-2">

					<CollapsibleHeader title="Slider" :collapsed="collapsed.has('images')" @toggle="toggle('images')" />
					<div v-show="!collapsed.has('images')" class="mt-20">

						<draggable
							v-if="sliderImages.length"
							:list="sliderImages"
							item-key="uuid"
							handle=".drag-handle"
							class="grid grid-cols-2 lg:grid-cols-4 gap-20"
							ghost-class="opacity-30"
							animation="150"
							@end="reorderSliderImages"
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

						<div :class="{ 'mt-20': sliderImages.length }">
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
				</Span>
				<Span v-show="!collapsed.has('file')" class="col-span-2 col-start-2">
					<template v-if="file">
						<MediaCard
              :item="file"
              :deletable="true"
              :editable="true"
              :show-filename="true"
              variant="dark"
              @delete="onFileDelete"
              @edit="editingMedia = $event" />
					</template>
          <template v-else>
            <MediaUploader
              :allowed-file-types="['.pdf', '.doc', '.docx', '.xls', '.xlsx', '.zip']"
              @uploaded="onFileUploaded" />
          </template>
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
