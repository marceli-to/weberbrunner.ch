<script setup>
import draggable from 'vuedraggable'
import { publicationBlocksApi } from '@/api/blocks'
import { useBlocks } from '@/composables/useBlocks'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import BlockCard from '@/components/blocks/BlockCard.vue'
import BlockSelector from '@/components/blocks/BlockSelector.vue'
import BlockTextForm from '@/components/blocks/BlockTextForm.vue'
import BlockLinksForm from '@/components/blocks/BlockLinksForm.vue'
import SectionTitleForm from '@/components/ui/SectionTitleForm.vue'
import BlockText from '@/components/icons/BlockText.vue'
import BlockLink from '@/components/icons/BlockLink.vue'

const props = defineProps({
	publication: { type: Object, required: true },
})

const emit = defineEmits(['updated'])

const {
	blocks, lastCreatedUuid, blockTitleForm,
	watchBlocks, addBlock, blockStoreFn, blockUpdateFn, onBlockStored,
	updateBlock, deleteBlock, reorderBlocks,
	addLink, saveLink, deleteLink, toggleLink, reorderLinks,
} = useBlocks(
	publicationBlocksApi,
	() => props.publication.uuid,
	emit,
	{ filterFn: b => b.type !== 'fixed-slider' },
)

watchBlocks(() => props.publication.blocks)

const blockTypes = [
	{ type: 'text', label: 'Text', icon: { component: BlockText, class: 'w-auto h-40', wrapperClass: 'flex justify-center' } },
	{ type: 'links', label: 'Link', icon: { component: BlockLink, class: 'w-auto h-40', wrapperClass: 'flex justify-center' } },
]
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
