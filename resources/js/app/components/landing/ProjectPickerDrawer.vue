<script setup>
import { ref } from 'vue'
import Drawer from '@/components/ui/drawer/Drawer.vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import RadioIcon from '@/components/icons/Radio.vue'

const props = defineProps({
	open: { type: Boolean, default: false },
	items: { type: Array, default: () => [] },
	modelValue: { type: [String, null], default: null },
	submitLabel: { type: String, default: 'Übernehmen' },
	cancelLabel: { type: String, default: 'Abbrechen' },
})

const emit = defineEmits(['update:modelValue', 'close', 'submit'])
const drawerView = ref('list')

function isSelected(uuid) {
	return props.modelValue === uuid
}

function select(uuid) {
	emit('update:modelValue', uuid)
	emit('submit')
}
</script>

<template>
	<Drawer
		:open="open"
		:views="[{ label: 'Text / Bilder', value: 'list' }, { label: 'Bilder', value: 'grid' }]"
		v-model:view="drawerView"
		:submit-label="submitLabel"
		:cancel-label="cancelLabel"
		@close="$emit('close')"
		@submit="$emit('submit')">

		<!-- List view -->
		<Grid v-if="drawerView === 'list'" :cols="6" class="mt-40">
			<Span class="col-span-4 col-start-2" v-for="item in items" :key="item.uuid">
				<Grid :cols="4">
					<Span class="col-span-3">
						<button type="button" class="flex items-start gap-x-10 border-t-thin border-t-white pt-10 cursor-pointer w-full text-left" @click="select(item.uuid)">
							<RadioIcon
								:variant="isSelected(item.uuid) ? 'checked' : 'unchecked'"
								class="w-12 shrink-0 mt-2 text-white" />
							<span class="text-white text-sm overflow-hidden text-ellipsis whitespace-nowrap">{{ item.full_title || item.title }}</span>
						</button>
					</Span>
					<Span class="col-span-1">
						<img
							v-if="item.teaser?.[0]"
							:src="item.teaser[0].thumbnail_url"
							:alt="item.full_title || item.title"
							class="w-full h-auto aspect-square object-cover bg-white cursor-pointer"
							@click="select(item.uuid)" />
						<div
							v-else
							class="w-full aspect-square bg-white/10 cursor-pointer"
							@click="select(item.uuid)" />
					</Span>
				</Grid>
			</Span>
		</Grid>

		<!-- Grid view -->
		<Grid v-if="drawerView === 'grid'" :cols="12" class="mt-40">
			<Span class="col-span-8 col-start-3">
				<Grid :cols="3">
					<button
						v-for="item in items"
						:key="item.uuid"
						type="button"
						class="cursor-pointer relative"
						@click="select(item.uuid)">
						<img
							v-if="item.teaser?.[0]"
							:src="item.teaser[0].thumbnail_url"
							:alt="item.full_title || item.title"
							class="w-full h-auto aspect-square object-cover bg-white" />
						<div
							v-else
							class="w-full aspect-square bg-white/10" />
						<span class="absolute top-10 left-10">
							<RadioIcon
								:variant="isSelected(item.uuid) ? 'checked' : 'unchecked'"
								class="w-12 text-white" />
						</span>
					</button>
				</Grid>
			</Span>
		</Grid>

	</Drawer>
</template>
