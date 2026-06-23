<script setup>
import { ref } from 'vue'
import draggable from 'vuedraggable'
import { pageBlocksApi } from '@/api/blocks'
import mediaApi from '@/api/media'
import { useToast } from '@/composables/useToast'
import { useBlocks } from '@/composables/useBlocks'
import { useCan } from '@/composables/useCan'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import BlockCard from '@/components/blocks/BlockCard.vue'
import BlockSelector from '@/components/blocks/BlockSelector.vue'
import BlockTextForm from '@/components/blocks/BlockTextForm.vue'
import BlockImageForm from '@/components/blocks/BlockImageForm.vue'
import BlockSliderForm from '@/components/blocks/BlockSliderForm.vue'
import BlockLinksForm from '@/components/blocks/BlockLinksForm.vue'
import MediaEditModal from '@/components/media/MediaEditModal.vue'
import SectionTitleForm from '@/components/ui/SectionTitleForm.vue'
import BlockText from '@/components/icons/BlockText.vue'
import BlockImage from '@/components/icons/BlockImage.vue'
import BlockGallery from '@/components/icons/BlockGallery.vue'
import BlockLink from '@/components/icons/BlockLink.vue'

const props = defineProps({
	page: { type: Object, required: true },
})

const emit = defineEmits(['updated'])

const toast = useToast()
const editingMedia = ref(null)
const { canCreate, canUpdate, canDelete, canReorder, canUpload } = useCan()

const {
	blocks, lastCreatedUuid, blockTitleForm,
	watchBlocks, addBlock, blockStoreFn, blockUpdateFn, onBlockStored,
	updateBlock, deleteBlock, reorderBlocks,
	addLink, saveLink, deleteLink, toggleLink, reorderLinks,
} = useBlocks(
	pageBlocksApi,
	() => props.page.page,
	emit,
)

watchBlocks(() => props.page.blocks)

const blockTypes = [
	{ type: 'text', label: 'Text', icon: { component: BlockText, class: 'w-auto h-40', wrapperClass: 'flex justify-center' } },
	{ type: 'slider', label: 'Slider', icon: { component: BlockGallery, class: 'w-auto h-40', wrapperClass: 'flex justify-center' } },
	{ type: 'image', label: 'Bild', icon: { component: BlockImage, class: 'w-auto h-40', wrapperClass: 'flex justify-center' } },
	{ type: 'links', label: 'Link', icon: { component: BlockLink, class: 'w-auto h-40', wrapperClass: 'flex justify-center' } },
]

async function uploadMedia(block, media) {
	const { data } = await mediaApi.persist(media)
	await pageBlocksApi.selectMedia(props.page.page, block.uuid, [data.data.uuid])
	emit('updated')
	toast.success('Bild hinzugefügt')
}

async function removeMedia(block, mediaUuid) {
	await pageBlocksApi.detachMedia(props.page.page, block.uuid, mediaUuid)
	emit('updated')
}

async function onEditSave({ uuid, data }) {
	await mediaApi.update(uuid, data)
	editingMedia.value = null
	toast.success('Bild gespeichert')
	emit('updated')
}

async function togglePublish(item) {
	await mediaApi.togglePublish(item.uuid)
	emit('updated')
}

async function reorderMedia(block, items) {
	await mediaApi.reorder(items)
	emit('updated')
}
</script>

<template>
	<Grid>

		<!-- Dynamic blocks -->
		<draggable
			v-if="blocks.length"
			v-model="blocks"
			item-key="uuid"
			handle=".drag-handle"
			:disabled="!canReorder"
			ghost-class="opacity-50"
			animation="150"
			class="col-span-10 flex flex-col gap-20"
			@end="reorderBlocks">

			<template #item="{ element }">

				<BlockCard
					:block="element"
					:initial-open="element.uuid === lastCreatedUuid"
					:flush="element.type === 'links'"
					:editable="canUpdate"
					:draggable="canReorder"
					:deletable="canDelete"
					@delete="deleteBlock(element)"
					@edit-title="blockTitleForm.edit($event)">

					<BlockTextForm
						v-if="element.type === 'text'"
						:block="element"
						@save="(data) => updateBlock(element, data)" />

					<BlockImageForm
						v-if="element.type === 'image'"
						:block="element"
						:allow-pick="false"
						:allow-upload="canUpload"
						@upload-media="(media) => uploadMedia(element, media)"
						@remove-media="(uuid) => removeMedia(element, uuid)"
						@toggle-publish="togglePublish"
						@edit-media="editingMedia = $event" />

					<BlockSliderForm
						v-if="element.type === 'slider'"
						:block="element"
						:allow-pick="false"
						:allow-upload="canUpload"
						@upload-media="(media) => uploadMedia(element, media)"
						@remove-media="(uuid) => removeMedia(element, uuid)"
						@reorder-media="(items) => reorderMedia(element, items)"
						@toggle-publish="togglePublish"
						@edit-media="editingMedia = $event" />

					<BlockLinksForm
						v-if="element.type === 'links'"
						:block="element"
						@add-link="(data) => addLink(element, data)"
						@save-link="(linkUuid, data) => saveLink(element, linkUuid, data)"
						@toggle-link="(linkUuid) => toggleLink(element, linkUuid)"
						@delete-link="(linkUuid) => deleteLink(element, linkUuid)"
						@reorder-links="(items) => reorderLinks(element, items)" />

				</BlockCard>

			</template>

		</draggable>

	</Grid>

	<!-- Block type picker -->
	<Grid class="mt-40">
		<template v-if="canCreate">
			<Span class="col-span-8 col-start-2">
				<BlockSelector :types="blockTypes" @select="addBlock" />
			</Span>
		</template>
	</Grid>

	<!-- Media edit lightbox -->
	<MediaEditModal
		:media="editingMedia"
		@save="onEditSave"
		@close="editingMedia = null" />

	<!-- Block title form (create + edit) -->
	<SectionTitleForm
		ref="blockTitleForm"
		label="Titel"
		create-label="Titel"
		:store-fn="blockStoreFn"
		:update-fn="blockUpdateFn"
		@stored="onBlockStored"
		@updated="$emit('updated')" />
</template>
