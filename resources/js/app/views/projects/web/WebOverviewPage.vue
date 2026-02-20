<script setup>
import { useRouter, useRoute } from 'vue-router'
import { useProject } from '@/composables/useProject'
import { useCollapsed } from '@/composables/useCollapsed'
import WebLayout from '@/views/projects/components/WebLayout.vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import CollapsibleHeader from '@/components/ui/CollapsibleHeader.vue'
import MediaCard from '@/components/media/MediaCard.vue'
import PencilCircle from '@/components/icons/PencilCircle.vue'
import ProjectBlocks from '@/views/projects/components/blocks/ProjectBlocks.vue'

const route = useRoute()
const router = useRouter()
const { project, fetch } = useProject()
const { collapsed, toggle } = useCollapsed('web-layout')

function editImages() {
	router.push({ name: 'projects.images.edit', params: { id: route.params.id } })
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
					<CollapsibleHeader
						title="Slider"
						:collapsed="collapsed.has('slider')"
						@toggle="toggle('slider')" />
					<div v-show="!collapsed.has('slider')" class="bg-white px-20 pb-20">
						<div v-if="project.media?.length" class="grid grid-cols-2 lg:grid-cols-5 gap-10 pt-10">
							<MediaCard
								v-for="item in project.media"
								:key="item.uuid"
								:item="item"
								compact />
						</div>
						<div class="flex justify-end pt-10">
							<button type="button" class="cursor-pointer" @click="editImages">
								<PencilCircle class="w-25" />
							</button>
						</div>
					</div>
				</Span>

			  <!-- Fixed: Projektbeschrieb -->
				<Span class="col-span-8 col-start-2">
					<CollapsibleHeader
						title="Projektbeschrieb"
						:collapsed="collapsed.has('description')"
						@toggle="toggle('description')" />
					<div v-show="!collapsed.has('description')" class="bg-white px-20 pb-20">
						<div class="text-sm pt-10" v-html="project.description" />
						<div class="flex justify-end pt-10">
							<button type="button" class="cursor-pointer" @click="editText">
								<PencilCircle class="w-25" />
							</button>
						</div>
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
