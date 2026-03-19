<script setup>
import { ref, computed, watch } from 'vue'
import draggable from 'vuedraggable'
import projectBlocksApi from '@/api/projectBlocks'
import mediaApi from '@/api/media'
import { useToast } from '@/composables/useToast'
import { useConfirm } from '@/composables/useConfirm'
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

const blockTypes = [
	{ type: 'text', label: 'Text', icon: { component: BlockText, class: 'w-auto h-40', wrapperClass: 'flex justify-center' } },
	{ type: 'slider', label: 'Slider', icon: { component: BlockGallery, class: 'w-auto h-40', wrapperClass: 'flex justify-center' } },
	{ type: 'image', label: 'Bild', icon: { component: BlockImage, class: 'w-auto h-40', wrapperClass: 'flex justify-center' } },
	{ type: 'links', label: 'Link', icon: { component: BlockLink, class: 'w-auto h-40', wrapperClass: 'flex justify-center' } },
]

const emit = defineEmits(['updated'])

const toast = useToast()
const { confirm } = useConfirm()
const editingMedia = ref(null)
const blockTitleForm = ref(null)

const blocks = ref([])
watch(() => props.project.blocks, (val) => {
	blocks.value = (val || []).filter(b => b.type !== 'fixed-slider')
}, { immediate: true })
const projectMedia = computed(() => props.project.media || [])
const lastCreatedUuid = ref(null)
const pendingType = ref(null)

function addBlock(type) {
	pendingType.value = type
	blockTitleForm.value.open()
}

async function blockStoreFn(title) {
	const response = await projectBlocksApi.store(props.project.uuid, { type: pendingType.value, title })
	lastCreatedUuid.value = response.data.data.uuid
	return response
}

function blockUpdateFn(uuid, title) {
	return projectBlocksApi.update(props.project.uuid, uuid, { title })
}

async function onBlockStored() {
	emit('updated')
	toast.success('Block hinzugefügt')
}

async function updateBlock(block, data) {
	await projectBlocksApi.update(props.project.uuid, block.uuid, data)
	emit('updated')
	toast.success('Block gespeichert')
}

async function deleteBlock(block) {
	const ok = await confirm({
		message: 'Möchtest Du diesen Block wirklich löschen?',
		confirmLabel: 'Löschen',
		variant: 'danger',
	})
	if (!ok) return
	await projectBlocksApi.destroy(props.project.uuid, block.uuid)
	emit('updated')
	toast.success('Block gelöscht')
}

async function reorderBlocks() {
	const items = blocks.value.map((block, index) => ({
		uuid: block.uuid,
		sort_order: index,
	}))
	await projectBlocksApi.reorder(props.project.uuid, items)
	emit('updated')
}

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

async function addLink(block, data) {
	await projectBlocksApi.storeLink(props.project.uuid, block.uuid, data)
	emit('updated')
}

async function saveLink(block, linkUuid, data) {
	await projectBlocksApi.updateLink(props.project.uuid, block.uuid, linkUuid, data)
	emit('updated')
	toast.success('Link gespeichert')
}

async function deleteLink(block, linkUuid) {
	const ok = await confirm({
		message: 'Möchtest Du diesen Link wirklich löschen?',
		confirmLabel: 'Löschen',
		variant: 'danger',
	})
	if (!ok) return
	await projectBlocksApi.destroyLink(props.project.uuid, block.uuid, linkUuid)
	emit('updated')
}

async function toggleLink(block, linkUuid) {
	await projectBlocksApi.toggleLink(props.project.uuid, block.uuid, linkUuid)
	emit('updated')
}

async function reorderLinks(block, items) {
	await projectBlocksApi.reorderLinks(props.project.uuid, block.uuid, items)
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
