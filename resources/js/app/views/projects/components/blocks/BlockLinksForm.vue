<script setup>
import { ref, watch } from 'vue'
import draggable from 'vuedraggable'
import Input from '@/components/ui/form/Input.vue'
import Button from '@/components/ui/form/Button.vue'
import PlusCircle from '@/components/icons/PlusCircle.vue'
import BlockLinkRow from '@/views/projects/components/blocks/BlockLinkRow.vue'

const props = defineProps({
	block: { type: Object, required: true },
	projects: { type: Array, default: () => [] },
})

const emit = defineEmits(['save', 'save-link', 'delete-link', 'add-link', 'reorder-links'])

const title = ref(props.block.title || '')

watch(() => props.block, (val) => {
	title.value = val.title || ''
})

const links = ref([...(props.block.links || [])])

watch(() => props.block.links, (val) => {
	links.value = [...(val || [])]
})

function onReorder() {
	const items = links.value.map((link, index) => ({
		id: link.id,
		sort_order: index,
	}))
	emit('reorder-links', items)
}

function save() {
	emit('save', { title: title.value })
}
</script>

<template>
	<div class="flex flex-col gap-y-10 pt-10">
		<Input
			v-model="title"
			placeholder="Titel" />

		<draggable
			v-if="links.length"
			v-model="links"
			item-key="uuid"
			handle=".drag-handle"
			ghost-class="opacity-30"
			animation="150"
			class="flex flex-col gap-y-10"
			@end="onReorder">
			<template #item="{ element }">
				<BlockLinkRow
					:link="element"
					:projects="projects"
					@save="(data) => $emit('save-link', element.uuid, data)"
					@delete="$emit('delete-link', element.uuid)" />
			</template>
		</draggable>

		<button
			type="button"
			class="flex items-center gap-x-5 text-sm cursor-pointer pt-5"
			@click="$emit('add-link')">
			<PlusCircle class="w-18" />
			<span>Link hinzufügen</span>
		</button>

		<div class="flex justify-end pt-5">
			<Button variant="primary" @click="save">Speichern</Button>
		</div>
	</div>
</template>
