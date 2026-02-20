<script setup>
import { ref, computed, watch } from 'vue'
import draggable from 'vuedraggable'
import projectBlocksApi from '@/api/projectBlocks'
import projectsApi from '@/api/projects'
import mediaApi from '@/api/media'
import { useToast } from '@/composables/useToast'
import { useConfirm } from '@/composables/useConfirm'
import { useFormErrors } from '@/composables/useFormErrors'
import { useLightbox } from '@/composables/useLightbox'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import Lightbox from '@/components/ui/lightbox/Lightbox.vue'
import Button from '@/components/ui/form/Button.vue'
import Input from '@/components/ui/form/Input.vue'
import BlockCard from '@/views/projects/components/blocks/BlockCard.vue'
import BlockAddMenu from '@/views/projects/components/blocks/BlockAddMenu.vue'
import BlockTextForm from '@/views/projects/components/blocks/BlockTextForm.vue'
import BlockImageForm from '@/views/projects/components/blocks/BlockImageForm.vue'
import BlockSliderForm from '@/views/projects/components/blocks/BlockSliderForm.vue'
import BlockLinksForm from '@/views/projects/components/blocks/BlockLinksForm.vue'
import MediaEditModal from '@/components/media/MediaEditModal.vue'

const props = defineProps({
	project: { type: Object, required: true },
})

const emit = defineEmits(['updated'])

const toast = useToast()
const { confirm } = useConfirm()
const { get, clear, submit } = useFormErrors()
const allProjects = ref([])
const editingMedia = ref(null)

const blocks = ref([])
watch(() => props.project.blocks, (val) => {
	blocks.value = (val || []).filter(b => b.type !== 'fixed-slider')
}, { immediate: true })
const projectMedia = computed(() => props.project.media || [])
const lastCreatedUuid = ref(null)

const pendingType = ref(null)
const blockTitle = ref('')
const { show: showTitleLightbox, open: openTitleLightbox, close: closeTitleLightbox } = useLightbox(() => {
	blockTitle.value = ''
	clear()
})

function addBlock(type) {
	pendingType.value = type
	openTitleLightbox()
}

async function storeBlock() {
	let response
	const ok = await submit(async () => {
		response = await projectBlocksApi.store(props.project.uuid, { type: pendingType.value, title: blockTitle.value })
	})
	if (!ok) return
	lastCreatedUuid.value = response.data.data.uuid
	closeTitleLightbox()
	emit('updated')
	toast.success('Block hinzugefügt')
	if (pendingType.value === 'links') {
		loadProjects()
	}
}

async function loadProjects() {
	if (allProjects.value.length) return
	const { data } = await projectsApi.index()
	allProjects.value = data.data
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
		id: block.id,
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
	const ok = await confirm({
		message: 'Möchtest Du dieses Bild wirklich entfernen?',
		confirmLabel: 'Entfernen',
		variant: 'danger',
	})
	if (!ok) return
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

				<BlockCard :block="element" :initial-open="element.uuid === lastCreatedUuid" :flush="element.type === 'links'" @delete="deleteBlock(element)">

					<BlockTextForm
						v-if="element.type === 'text'"
						:block="element"
						@save="(data) => updateBlock(element, data)" />

					<BlockImageForm
						v-if="element.type === 'image'"
						:block="element"
						:project-media="projectMedia"
						@select-media="(uuids) => selectMedia(element, uuids)"
						@remove-media="(uuid) => removeMedia(element, uuid)"
						@toggle-publish="togglePublish"
						@edit-media="editingMedia = $event" />

					<BlockSliderForm
						v-if="element.type === 'slider'"
						:block="element"
						:project-media="projectMedia"
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
		  <BlockAddMenu @select="addBlock" />
    </Span>
  </Grid>

	<!-- Media edit lightbox -->
	<MediaEditModal
		:media="editingMedia"
		@save="onEditSave"
		@close="editingMedia = null" />

	<!-- Title lightbox -->
	<Lightbox :open="showTitleLightbox" title="Neuer Block" @close="closeTitleLightbox" :closeable="false">
		<form @submit.prevent="storeBlock" class="px-20">
			<Input v-model="blockTitle" :error="get('title')" placeholder="Titel" class="form-input form-input--lg" @focus="clear('title')" />
			<div class="flex gap-20 mt-20">
				<Button type="submit" class="flex justify-center">Speichern</Button>
				<Button @click="closeTitleLightbox" class="flex justify-center">Abbrechen</Button>
			</div>
		</form>
	</Lightbox>
</template>
