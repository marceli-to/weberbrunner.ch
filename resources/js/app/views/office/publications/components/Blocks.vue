<script setup>
import { ref, watch } from 'vue'
import draggable from 'vuedraggable'
import publicationsApi from '@/api/publications'
import { useToast } from '@/composables/useToast'
import { useConfirm } from '@/composables/useConfirm'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import BlockCard from '@/components/blocks/BlockCard.vue'
import BlockSelector from '@/components/blocks/BlockSelector.vue'
import BlockTextForm from '@/components/blocks/BlockTextForm.vue'
import SectionTitleForm from '@/components/ui/SectionTitleForm.vue'
import BlockText from '@/components/icons/BlockText.vue'

const props = defineProps({
	publication: { type: Object, required: true },
})

const emit = defineEmits(['updated'])

const toast = useToast()
const { confirm } = useConfirm()
const blockTitleForm = ref(null)

const blocks = ref([])
watch(() => props.publication.blocks, (val) => {
	blocks.value = val || []
}, { immediate: true })
const lastCreatedUuid = ref(null)
const pendingType = ref(null)

const blockTypes = [
	{ type: 'text', label: 'Text', icon: { component: BlockText, class: 'w-auto h-40', wrapperClass: 'flex justify-center' } },
]

function addBlock(type) {
	pendingType.value = type
	blockTitleForm.value.open()
}

async function blockStoreFn(title) {
	const response = await publicationsApi.blocks.store(props.publication.uuid, { type: pendingType.value, title })
	lastCreatedUuid.value = response.data.data.uuid
	return response
}

function blockUpdateFn(uuid, title) {
	return publicationsApi.blocks.update(props.publication.uuid, uuid, { title })
}

async function onBlockStored() {
	emit('updated')
	toast.success('Block hinzugefügt')
}

async function updateBlock(block, data) {
	await publicationsApi.blocks.update(props.publication.uuid, block.uuid, data)
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
	await publicationsApi.blocks.destroy(props.publication.uuid, block.uuid)
	emit('updated')
	toast.success('Block gelöscht')
}

async function reorderBlocks() {
	const items = blocks.value.map((block, index) => ({
		uuid: block.uuid,
		sort_order: index,
	}))
	await publicationsApi.blocks.reorder(props.publication.uuid, items)
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
					editable
					@delete="deleteBlock(element)"
					@edit-title="blockTitleForm.edit($event)">

					<BlockTextForm
						v-if="element.type === 'text'"
						:block="element"
						@save="(data) => updateBlock(element, data)" />

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
