<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import jobsApi from '@/api/jobs'
import { useCollapsed } from '@/composables/useCollapsed'
import { useConfirm } from '@/composables/useConfirm'
import draggable from 'vuedraggable'
import PageTitle from '@/components/ui/PageTitle.vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import Button from '@/components/ui/form/Button.vue'
import Burger from '@/components/icons/Burger.vue'
import CollapsibleHeader from '@/components/ui/CollapsibleHeader.vue'
import Cross from '@/components/icons/Cross.vue'
import Eye from '@/components/icons/Eye.vue'
import Pencil from '@/components/icons/Pencil.vue'
import Plus from '@/components/icons/Plus.vue'

const router = useRouter()
const groups = ref([])
const { collapsed, toggle: toggleLocation } = useCollapsed('jobs')
const { confirm } = useConfirm()

async function fetchJobs() {
	const { data } = await jobsApi.index()
	groups.value = data.data
}

async function deleteJob(job) {
	const ok = await confirm({
		message: 'Möchtest Du diesen Eintrag wirklich löschen?',
		confirmLabel: 'Löschen',
		variant: 'danger',
	})
	if (ok) {
		await jobsApi.destroy(job.uuid)
		await fetchJobs()
	}
}

async function togglePublish(job) {
	job.publish = !job.publish
	await jobsApi.toggle(job.uuid)
}

async function reorderJobs(group) {
	const items = group.jobs.map((j, i) => ({
		id: j.id,
		sort_order: i,
		location_id: group.location.id,
	}))
	await jobsApi.reorder(items)
}

onMounted(fetchJobs)
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
								@change="reorderJobs(group)">
								<template #item="{ element: job }">
									<Grid :cols="10">
										<Span class="col-span-1 flex items-center justify-end">
											<Burger variant="sm" class="w-18 h-10 cursor-grab job-drag-handle" />
										</Span>
										<Span class="col-span-8">
											<div class="bg-white font-semibold min-h-30 border border-black flex justify-between items-center px-20 select-none" :class="{ 'opacity-50': !job.publish }">
												<span>
													{{ job.title }}
												</span>
												<span class="flex gap-x-20">
													<Pencil class="w-14 cursor-pointer" @click="router.push({ name: 'jobs.edit', params: { id: job.uuid } })" />
													<Eye :variant="job.publish ? 'visible' : 'hidden'" class="w-14 cursor-pointer" @click="togglePublish(job)" />
												</span>
											</div>
										</Span>
										<Span class="col-span-1 flex items-center justify-start">
											<Cross class="w-10 cursor-pointer" @click="deleteJob(job)" />
										</Span>
									</Grid>
								</template>
							</draggable>

							<Grid :cols="10" class="mb-10">
								<Span class="col-span-8 col-start-2">
									<Button class="px-20" @click="router.push({ name: 'jobs.create', query: { location: group.location.uuid } })">
										<template #icon-right>
											<Plus class="w-10 h-10" />
										</template>
										Neuer Eintrag
									</Button>
								</Span>
							</Grid>

						</Span>

					</Grid>

				</Span>

			</template>

		</div>

	</Grid>

</template>
