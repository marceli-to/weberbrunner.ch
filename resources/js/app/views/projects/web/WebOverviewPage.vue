<script setup>
import { ref, computed } from 'vue'
import draggable from 'vuedraggable'
import { useRouter, useRoute } from 'vue-router'
import { useProject } from '@/composables/useProject'
import { useCollapsed } from '@/composables/useCollapsed'
import { useConfirm } from '@/composables/useConfirm'
import projectBlocksApi from '@/api/projectBlocks'
import mediaApi from '@/api/media'
import WebLayout from '@/views/projects/components/WebLayout.vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import CollapsibleHeader from '@/components/ui/CollapsibleHeader.vue'
import MediaCard from '@/components/media/MediaCard.vue'
import PencilCircle from '@/components/icons/PencilCircle.vue'
import ProjectBlocks from '@/views/projects/components/blocks/ProjectBlocks.vue'
import AddButton from '@/components/media/AddButton.vue'
import MediaPickerDrawer from '@/components/media/MediaPickerDrawer.vue'

const route = useRoute()
const router = useRouter()
const { project, fetch } = useProject()
const { collapsed, toggle } = useCollapsed('web-layout')
const { confirm } = useConfirm()

const sliderDrawerOpen = ref(false)
const selectedSliderUuids = ref([])

const fixedSliderBlock = computed(() =>
	(project.value?.blocks || []).find(b => b.type === 'fixed-slider')
)

const sliderImages = computed(() =>
	fixedSliderBlock.value?.media || []
)

async function onSliderSubmit() {
	const projectUuid = project.value.uuid
	let block = fixedSliderBlock.value
	if (!block) {
		const { data } = await projectBlocksApi.store(projectUuid, { type: 'fixed-slider' })
		block = data.data
	}
	await projectBlocksApi.selectMedia(projectUuid, block.uuid, selectedSliderUuids.value)
	sliderDrawerOpen.value = false
	selectedSliderUuids.value = []
	await fetch()
}

async function removeSliderImage(item) {
	const ok = await confirm({
		message: 'Möchtest Du dieses Bild wirklich entfernen?',
		confirmLabel: 'Entfernen',
		variant: 'danger',
	})
	if (!ok) return
	await projectBlocksApi.detachMedia(project.value.uuid, fixedSliderBlock.value.uuid, item.uuid)
	await fetch()
}

async function reorderSliderImages() {
	const items = sliderImages.value.map((m, index) => ({
		uuid: m.uuid,
		sort_order: index,
	}))
	await mediaApi.reorder(items)
	await fetch()
}

function editText() {
	router.push({ name: 'projects.text.edit', params: { id: route.params.id } })
}
</script>

<template>
	<WebLayout :project="project">

		<template v-if="project">

			<Grid class="mb-20">

        <!-- Fixed: Slider -->
				<Span class="col-span-8 col-start-2">

					<CollapsibleHeader title="Slider" :collapsed="collapsed.has('slider')" @toggle="toggle('slider')" />

					<div v-show="!collapsed.has('slider')" class="mt-20">
						<draggable
							v-if="sliderImages.length"
							:list="sliderImages"
							item-key="uuid"
							handle=".drag-handle"
							class="grid grid-cols-4 gap-20"
							ghost-class="opacity-30"
							animation="150"
							@end="reorderSliderImages">
							<template #item="{ element }">
								<MediaCard :item="element" draggable deletable @delete="removeSliderImage" />
							</template>
							<template #footer>
								<AddButton @click="sliderDrawerOpen = true" />
							</template>
						</draggable>
						<AddButton v-else @click="sliderDrawerOpen = true" />
					</div>

					<MediaPickerDrawer
						:open="sliderDrawerOpen"
						:items="project.media"
						v-model="selectedSliderUuids"
						multiple
						@close="sliderDrawerOpen = false"
						@submit="onSliderSubmit" />

				</Span>

			  <!-- Fixed: Projektbeschrieb -->
				<Span class="col-span-8 col-start-2">

					<CollapsibleHeader
						title="Projektbeschrieb"
						:collapsed="collapsed.has('description')"
						@toggle="toggle('description')" />

					<div v-show="!collapsed.has('description')" class="bg-white px-20 pb-20">

						<div class="text-md font-semibold max-w-4xl py-10" v-html="project.description" />

					</div>

				</Span>

			  <!-- Fixed: Stammdaten -->
				<Span class="col-span-8 col-start-2">
					<CollapsibleHeader
						title="Stammdaten"
						:collapsed="collapsed.has('masterdata')"
						@toggle="toggle('masterdata')" />
          <div v-show="!collapsed.has('masterdata')" class="bg-white p-20">
            [Stammdaten]
          </div>
				</Span>

      </Grid>

			<!-- Dynamic blocks + block type picker -->
			<ProjectBlocks :project="project" @updated="fetch" />

		</template>
	</WebLayout>
</template>
