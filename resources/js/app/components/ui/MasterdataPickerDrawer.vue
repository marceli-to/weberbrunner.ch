<script setup>
import { ref, watch } from 'vue'
import projectMasterdataApi from '@/api/project-masterdata'
import Drawer from '@/components/ui/drawer/Drawer.vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import CheckboxIcon from '@/components/icons/Checkbox.vue'

const props = defineProps({
	open: { type: Boolean, default: false },
	projectUuid: { type: String, required: true },
})

const emit = defineEmits(['close', 'change'])

const entries = ref([])

watch(() => props.open, async (val) => {
	if (val) {
		const { data } = await projectMasterdataApi.available(props.projectUuid)
		entries.value = data.data
	}
})

async function toggle(entry) {
	if (entry.publish) {
		await projectMasterdataApi.destroy(props.projectUuid, entry.uuid)
		entry.publish = false
	} else {
		await projectMasterdataApi.attach(props.projectUuid, entry.uuid)
		entry.publish = true
	}
	emit('change')
}
</script>

<template>
	<Drawer :open="open" @close="$emit('close')">
		<Grid :cols="6" class="mt-40">
			<Span class="col-span-4 col-start-2 flex flex-col">
				<button
					v-for="entry in entries"
					:key="entry.uuid"
					type="button"
					class="flex items-center gap-x-10 border-t-thin border-t-white/20 py-10 cursor-pointer w-full text-left"
					@click="toggle(entry)">
					<CheckboxIcon
						:variant="entry.publish ? 'checked' : 'unchecked'"
						class="w-12 shrink-0 text-white" />
					<span class="text-white text-sm">{{ entry.title }}</span>
				</button>
			</Span>
		</Grid>
	</Drawer>
</template>
