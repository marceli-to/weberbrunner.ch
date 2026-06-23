<script setup>
import { ref, watch } from 'vue'
import draggable from 'vuedraggable'
import publicationsApi from '@/api/publications'
import { useConfirm } from '@/composables/useConfirm'
import { useCan } from '@/composables/useCan'
import Burger from '@/components/icons/Burger.vue'
import Cross from '@/components/icons/Cross.vue'
import EntryRow from '@/components/ui/EntryRow.vue'
import NewEntryButton from '@/components/ui/NewEntryButton.vue'
import AttributeLightbox from '@/views/office/publications/components/AttributeLightbox.vue'

const props = defineProps({
	publication: { type: Object, required: true },
})

const emit = defineEmits(['updated'])

const { confirm } = useConfirm()
const { canCreate, canUpdate, canDelete, canReorder } = useCan()
const lightbox = ref(null)
const attributes = ref([])

watch(() => props.publication?.attributes, (val) => {
	attributes.value = val ? [...val] : []
}, { immediate: true })

async function reorder() {
	const items = attributes.value.map((e, i) => ({ uuid: e.uuid, sort_order: i }))
	await publicationsApi.attributes.reorder(props.publication.uuid, items)
}

async function destroy(attribute) {
	const ok = await confirm({
		message: 'Möchtest Du diesen Eintrag wirklich löschen?',
		confirmLabel: 'Entfernen',
		variant: 'danger',
	})
	if (!ok) return
	await publicationsApi.attributes.destroy(props.publication.uuid, attribute.uuid)
	emit('updated')
}
</script>

<template>
	<draggable
		v-model="attributes"
		item-key="uuid"
		handle=".attribute-drag-handle"
		:disabled="!canReorder"
		ghost-class="opacity-50"
		animation="150"
		class="flex flex-col gap-10 min-h-1"
		:class="{ 'mb-10': attributes.length }"
		@end="reorder">
		<template #item="{ element }">
			<div class="flex items-center gap-20">
				<template v-if="canReorder">
					<Burger variant="sm" class="w-18 h-10 cursor-grab attribute-drag-handle flex-none" />
				</template>
				<EntryRow
					:label="element.key"
					:sublabel="element.value"
					:editable="canUpdate"
					:show-publish="false"
					class="flex-1"
					split
					@edit="lightbox.open(publication, element)" />
				<template v-if="canDelete">
					<Cross class="w-10 cursor-pointer flex-none" @click="destroy(element)" />
				</template>
			</div>
		</template>
	</draggable>

	<template v-if="canCreate">
		<div class="mt-10" :class="{ 'ml-38 mr-30': attributes.length }">
			<NewEntryButton @click="lightbox.open(publication)">Hinzufügen</NewEntryButton>
		</div>
	</template>

	<AttributeLightbox ref="lightbox" @saved="emit('updated')" />
</template>
