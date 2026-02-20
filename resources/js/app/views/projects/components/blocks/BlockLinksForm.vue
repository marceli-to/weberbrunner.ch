<script setup>
import { ref, watch } from 'vue'
import draggable from 'vuedraggable'
import DraggableEntryRow from '@/components/ui/DraggableEntryRow.vue'
import NewEntryButton from '@/components/ui/NewEntryButton.vue'
import AppDialog from '@/components/ui/dialog/AppDialog.vue'
import Button from '@/components/ui/form/Button.vue'
import LinkDialogFields from '@/components/ui/form/LinkDialogFields.vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'

const props = defineProps({
	block: { type: Object, required: true },
})

const emit = defineEmits(['add-link', 'save-link', 'toggle-link', 'delete-link', 'reorder-links'])

const links = ref([...(props.block.links || [])])

watch(() => props.block.links, (val) => {
	links.value = [...(val || [])]
})

const dialogOpen = ref(false)
const editingLink = ref(null)
const formRef = ref(null)

function openCreate() {
	editingLink.value = null
	dialogOpen.value = true
}

function openEdit(link) {
	editingLink.value = link
	dialogOpen.value = true
}

function closeDialog() {
	dialogOpen.value = false
	editingLink.value = null
}

function buildPayload() {
	const data = formRef.value?.getFormData()
	return {
		title: data.title,
		link_type: data.mode,
		url: data.mode === 'external' ? data.url : null,
		linked_project_id: data.mode === 'internal' && data.selectedProject ? data.selectedProject.id : null,
	}
}

function save() {
	if (!formRef.value?.validate()) return
	const payload = buildPayload()
	if (editingLink.value) {
		emit('save-link', editingLink.value.uuid, payload)
	} else {
		emit('add-link', payload)
	}
	closeDialog()
}

function onReorder() {
	const items = links.value.map((link, index) => ({
		uuid: link.uuid,
		sort_order: index,
	}))
	emit('reorder-links', items)
}
</script>

<template>
	<draggable
		v-if="links.length"
		v-model="links"
		item-key="uuid"
		handle=".link-drag-handle"
		ghost-class="opacity-50"
		animation="150"
		class="flex flex-col gap-10"
		:class="{ 'mb-10': links.length }"
		@end="onReorder">
		<template #item="{ element }">
			<DraggableEntryRow
				:label="element.title || element.url || '(kein Titel)'"
				:publish="element.publish"
				drag-handle-class="link-drag-handle"
				@edit="openEdit(element)"
				@toggle-publish="$emit('toggle-link', element.uuid)"
				@delete="$emit('delete-link', element.uuid)" />
		</template>
	</draggable>

	<NewEntryButton @click="openCreate" />

	<AppDialog :open="dialogOpen" :title="editingLink ? 'Link bearbeiten' : 'Link'" @close="closeDialog">
		<LinkDialogFields
			v-if="dialogOpen"
			ref="formRef"
			:mode="editingLink?.link_type || 'external'"
			:url="editingLink?.url || ''"
			:title="editingLink?.title || ''"
			:selected-project-id="editingLink?.linked_project_id" />

		<template #footer>
			<Grid :cols="2">
				<Span><Button class="justify-center" @click="save">Übernehmen</Button></Span>
				<Span><Button class="justify-center" @click="closeDialog">Abbrechen</Button></Span>
			</Grid>
		</template>
	</AppDialog>
</template>
