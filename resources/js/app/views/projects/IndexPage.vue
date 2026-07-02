<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import projectsApi from '@/api/projects'
import { usePageLoader } from '@/composables/usePageLoader'
import { useTableSort } from '@/composables/useTableSort'
import { useCan } from '@/composables/useCan'
import PageTitle from '@/components/ui/PageTitle.vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import Button from '@/components/ui/form/Button.vue'
import Plus from '@/components/icons/Plus.vue'
import ListTable from '@/components/ui/list/ListTable.vue'
import ListTableRow from '@/components/ui/list/ListTableRow.vue'
import ListTableCell from '@/components/ui/list/ListTableCell.vue'
import CreateLightbox from '@/views/projects/components/CreateLightbox.vue'

const router = useRouter()
const { load } = usePageLoader()
const projects = ref([])
const createLightbox = ref(null)
const { sorted, sortKey, sortDir, toggleSort } = useTableSort(projects, 'priority', 'asc', 'projects')
const { canCreate } = useCan()

async function fetch() {
	const { data } = await projectsApi.index()
	projects.value = data.data
}

function onCreated(project) {
	if (project?.uuid) {
		router.push({ name: 'projects.show', params: { id: project.uuid } })
	}
}

load(fetch)
</script>

<template>

	<Grid class="mb-10">

		<Span class="col-span-8 col-start-2">
			<PageTitle>Arbeiten</PageTitle>
		</Span>

		<template v-if="canCreate">
			<Span class="col-span-8 col-start-2 mb-20">
				<Button @click="createLightbox.open()" class="px-20">
					<template #icon-right>
						<Plus class="w-10 h-10" />
					</template>
					Neues Projekt
				</Button>
			</Span>
		</template>

		<Span class="col-span-8 col-start-2">
			<ListTable :cols="16">

				<!-- Header -->
				<ListTableRow header>
					<ListTableCell span="col-span-1" first header sortable :sort-active="sortKey === 'priority'" :sort-dir="sortDir" @sort="toggleSort('priority')">
						Prio
					</ListTableCell>
					<ListTableCell span="col-span-2" header sortable :sort-active="sortKey === 'number'" :sort-dir="sortDir" @sort="toggleSort('number')">
						Nr.
					</ListTableCell>
					<ListTableCell span="col-span-8" header sortable :sort-active="sortKey === 'title'" :sort-dir="sortDir" @sort="toggleSort('title')">
						Projektname
					</ListTableCell>
					<ListTableCell span="col-span-4" header sortable :sort-active="sortKey === 'city'" :sort-dir="sortDir" @sort="toggleSort('city')">
						Ort
					</ListTableCell>
					<ListTableCell span="col-span-1" last header>
						&nbsp;
					</ListTableCell>
				</ListTableRow>

				<!-- Entries -->
				<ListTableRow v-for="project in sorted" :key="project.uuid" :to="{ name: 'projects.show', params: { id: project.uuid } }">
					<ListTableCell span="col-span-1" first>
						{{ project.priority }}
					</ListTableCell>
					<ListTableCell span="col-span-2">
						{{ project.number }}
					</ListTableCell>
					<ListTableCell span="col-span-8">
						<span class="block truncate">
							{{ project.title }}
						</span>
					</ListTableCell>
					<ListTableCell span="col-span-4">
						<span class="block truncate">
							{{ project.city }}
						</span>
					</ListTableCell>
					<ListTableCell span="col-span-1" last>
            <template v-if="!project.publish">
						<span class="w-full flex items-center justify-center">
							<span class="rounded-full bg-lime w-8 h-8"></span>
						</span>
            </template>
					</ListTableCell>
				</ListTableRow>

			</ListTable>
		</Span>

	</Grid>

	<CreateLightbox ref="createLightbox" @created="onCreated" />

</template>
