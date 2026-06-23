<script setup>
import { ref, onMounted } from 'vue'
import draggable from 'vuedraggable'
import projectMasterdataApi from '@/api/project-masterdata'
import { useConfirm } from '@/composables/useConfirm'
import { useCan } from '@/composables/useCan'
import Burger from '@/components/icons/Burger.vue'
import Cross from '@/components/icons/Cross.vue'
import EntryRow from '@/components/ui/EntryRow.vue'
import MasterdataPickerDrawer from '@/components/ui/MasterdataPickerDrawer.vue'
import NewEntryButton from '@/components/ui/NewEntryButton.vue'

const props = defineProps({
	project: { type: Object, required: true },
})

const { confirm } = useConfirm()
const { canCreate, canDelete, canReorder } = useCan()
const entries = ref([])
const drawerOpen = ref(false)

onMounted(fetch)

async function fetch() {
	const { data } = await projectMasterdataApi.attached(props.project.uuid)
	entries.value = data.data
}

async function reorder() {
	const items = entries.value.map((e, i) => ({ uuid: e.uuid, sort_order: i }))
	await projectMasterdataApi.reorder(props.project.uuid, items)
}

async function destroy(entry) {
	const ok = await confirm({
		message: 'Möchtest Du diesen Eintrag wirklich entfernen?',
		confirmLabel: 'Entfernen',
		variant: 'danger',
	})
	if (!ok) return
	await projectMasterdataApi.destroy(props.project.uuid, entry.uuid)
	await fetch()
}
</script>

<template>
	<draggable
		v-model="entries"
		item-key="uuid"
		handle=".masterdata-drag-handle"
		:disabled="!canReorder"
		ghost-class="opacity-50"
		animation="150"
		class="flex flex-col gap-10 min-h-1"
		:class="{ 'mb-10': entries.length }"
		@end="reorder">
		<template #item="{ element }">
			<div class="flex items-center gap-20">
				<Burger v-if="canReorder" variant="sm" class="w-18 h-10 cursor-grab masterdata-drag-handle flex-none" />
				<EntryRow
					:label="element.title"
					:sublabel="element.value"
					:editable="false"
					:show-publish="false"
					class="flex-1"
					split />
				<Cross v-if="canDelete" class="w-10 cursor-pointer flex-none" @click="destroy(element)" />
			</div>
		</template>
	</draggable>

  <div v-if="canCreate" class="mt-10 ml-38 mr-30">
    <NewEntryButton @click="drawerOpen = true">Hinzufügen</NewEntryButton>
  </div>

	<MasterdataPickerDrawer
		:open="drawerOpen"
		:project-uuid="project.uuid"
		@close="drawerOpen = false; fetch()"
		@change="fetch" />
</template>
