<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import jobsApi from '@/api/jobs'
import { usePageLoader } from '@/composables/usePageLoader'
import { useCollapsed } from '@/composables/useCollapsed'
import { useConfirm } from '@/composables/useConfirm'
import { useCan } from '@/composables/useCan'
import draggable from 'vuedraggable'
import PageTitle from '@/components/ui/PageTitle.vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import CollapsibleHeader from '@/components/ui/CollapsibleHeader.vue'
import DraggableEntryRow from '@/components/ui/DraggableEntryRow.vue'
import NewEntryButton from '@/components/ui/NewEntryButton.vue'

const router = useRouter()
const { load } = usePageLoader()
const groups = ref([])
const { collapsed, toggle: toggleLocation } = useCollapsed('jobs')
const { confirm } = useConfirm()
const { canCreate, canUpdate, canDelete, canReorder, canPublish } = useCan()

async function fetch() {
	const { data } = await jobsApi.index()
	groups.value = data.data
}

async function destroy(job) {
	const ok = await confirm({
		message: 'Möchtest Du diesen Eintrag wirklich löschen?',
		confirmLabel: 'Löschen',
		variant: 'danger',
	})
	if (ok) {
		await jobsApi.destroy(job.uuid)
		await fetch()
	}
}

async function toggle(job) {
	job.publish = !job.publish
	await jobsApi.toggle(job.uuid)
}

async function reorder(group) {
	const items = group.jobs.map((j, i) => ({
		uuid: j.uuid,
		sort_order: i,
		location_id: group.location.id,
	}))
	await jobsApi.reorder(items)
}

load(fetch)
</script>

<template>

	<!-- Header -->
	<Grid class="mb-40">
		<Span class="col-span-8 col-start-2">
			<PageTitle>Jobs</PageTitle>
		</Span>
	</Grid>

	<!-- Jobs -->
	<Grid>

		<div class="col-span-10 flex flex-col gap-20">

			<template v-for="group in groups" :key="group.location.uuid">

				<Span class="col-span-10">

					<Grid :cols="10">

						<!-- Location header -->
						<Span class="col-span-8 col-start-2">
							<CollapsibleHeader
								:title="group.location.title"
								:collapsed="collapsed.has(group.location.uuid)"
								@toggle="toggleLocation(group.location.uuid)" />
						</Span>

						<!-- Job entries -->
						<Span v-show="!collapsed.has(group.location.uuid)" class="col-span-10 col-start-1">
							<draggable
								v-model="group.jobs"
								item-key="uuid"
								handle=".job-drag-handle"
								ghost-class="opacity-50"
								animation="150"
								class="flex flex-col gap-10 min-h-1"
								:class="{ 'mb-10': group.jobs.length }"
								:disabled="!canReorder"
								@change="reorder(group)">
								<template #item="{ element: job }">
									<DraggableEntryRow
										:label="job.title"
										:publish="job.publish"
										:editable="canUpdate"
										:show-publish="canPublish"
										:draggable="canReorder"
										:deletable="canDelete"
										drag-handle-class="job-drag-handle"
										@edit="router.push({ name: 'jobs.edit', params: { id: job.uuid } })"
										@toggle-publish="toggle(job)"
										@delete="destroy(job)" />
								</template>
							</draggable>

							<Grid :cols="10" class="mb-10">
								<Span class="col-span-8 col-start-2">
									<template v-if="canCreate">
										<NewEntryButton @click="router.push({ name: 'jobs.create', query: { location: group.location.uuid } })" />
									</template>
								</Span>
							</Grid>

						</Span>

					</Grid>

				</Span>

			</template>

		</div>

	</Grid>

</template>
