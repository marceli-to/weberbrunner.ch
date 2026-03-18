<script setup>
import { ref, onMounted } from 'vue'
import draggable from 'vuedraggable'
import projectMasterdataApi from '@/api/project-masterdata'
import { useConfirm } from '@/composables/useConfirm'
import DraggableEntryRow from '@/components/ui/DraggableEntryRow.vue'
import MasterdataPickerDrawer from '@/components/projects/MasterdataPickerDrawer.vue'
import NewEntryButton from '@/components/ui/NewEntryButton.vue'

const props = defineProps({
	project: { type: Object, required: true },
})

const { confirm } = useConfirm()
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
		ghost-class="opacity-50"
		animation="150"
		class="flex flex-col gap-10 min-h-1"
		:class="{ 'mb-10': entries.length }"
		@end="reorder">
		<template #item="{ element }">
			<DraggableEntryRow
				:label="element.title"
				:sublabel="element.value"
				:editable="false"
				:show-publish="false"
				drag-handle-class="masterdata-drag-handle"
				split
				@delete="destroy(element)" />
		</template>
	</draggable>

	<NewEntryButton class="mt-10" @click="drawerOpen = true">Hinzufügen</NewEntryButton>

	<MasterdataPickerDrawer
		:open="drawerOpen"
		:project-uuid="project.uuid"
		@close="drawerOpen = false; fetch()"
		@change="fetch" />
</template>
