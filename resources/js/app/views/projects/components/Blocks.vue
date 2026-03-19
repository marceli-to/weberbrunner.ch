<script setup>
import { ref, computed } from 'vue'
import draggable from 'vuedraggable'
import projectBlocksApi from '@/api/projectBlocks'
import mediaApi from '@/api/media'
import { useToast } from '@/composables/useToast'
import { useBlocks } from '@/composables/useBlocks'
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
	project: { type: Object, required: true },
})

const emit = defineEmits(['updated'])

const toast = useToast()
const editingMedia = ref(null)

const {
	blocks, lastCreatedUuid, blockTitleForm,
	watchBlocks, addBlock, blockStoreFn, blockUpdateFn, onBlockStored,
	updateBlock, deleteBlock, reorderBlocks,
	addLink, saveLink, deleteLink, toggleLink, reorderLinks,
} = useBlocks(
	projectBlocksApi,
	() => props.project.uuid,
	emit,
	{ filterFn: b => b.type !== 'fixed-slider' },
)

watchBlocks(() => props.project.blocks)

const blockTypes = [
	{ type: 'text', label: 'Text', icon: { component: BlockText, class: 'w-auto h-40', wrapperClass: 'flex justify-center' } },
	{ type: 'slider', label: 'Slider', icon: { component: BlockGallery, class: 'w-auto h-40', wrapperClass: 'flex justify-center' } },
	{ type: 'image', label: 'Bild', icon: { component: BlockImage, class: 'w-auto h-40', wrapperClass: 'flex justify-center' } },
	{ type: 'links', label: 'Link', icon: { component: BlockLink, class: 'w-auto h-40', wrapperClass: 'flex justify-center' } },
]

const projectMedia = computed(() => props.project.media || [])

async function selectMedia(block, mediaUuids) {
	await projectBlocksApi.selectMedia(props.project.uuid, block.uuid, mediaUuids)
	emit('updated')
	toast.success('Bild hinzugefügt')
}

async function removeMedia(block, mediaUuid) {
	await projectBlocksApi.detachMedia(props.project.uuid, block.uuid, mediaUuid)
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
			ghost-class="opacity-50"
			animation="150"
			class="col-span-10 flex flex-col gap-20"
			@end="reorderBlocks">

			<template #item="{ element }">

				<BlockCard
					:block="element"
					:initial-open="element.uuid === lastCreatedUuid"
					:flush="element.type === 'links'"
					editable
					@delete="deleteBlock(element)"
					@edit-title="blockTitleForm.edit($event)">

					<BlockTextForm
						v-if="element.type === 'text'"
						:block="element"
						@save="(data) => updateBlock(element, data)" />

					<BlockImageForm
						v-if="element.type === 'image'"
						:block="element"
						:media-pool="projectMedia"
						@select-media="(uuids) => selectMedia(element, uuids)"
						@remove-media="(uuid) => removeMedia(element, uuid)"
						@toggle-publish="togglePublish"
						@edit-media="editingMedia = $event" />

					<BlockSliderForm
						v-if="element.type === 'slider'"
						:block="element"
						:media-pool="projectMedia"
						@select-media="(uuids) => selectMedia(element, uuids)"
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
		<Span class="col-span-8 col-start-2">
			<BlockSelector :types="blockTypes" @select="addBlock" />
		</Span>
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
