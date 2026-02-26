<script setup>
import { ref } from 'vue'
import projectsApi from '@/api/projects'
import { usePageLoader } from '@/composables/usePageLoader'
import { useTableSort } from '@/composables/useTableSort'
import PageTitle from '@/components/ui/PageTitle.vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import ListTable from '@/components/ui/list/ListTable.vue'
import ListTableRow from '@/components/ui/list/ListTableRow.vue'
import ListTableCell from '@/components/ui/list/ListTableCell.vue'

const { load } = usePageLoader()
const projects = ref([])
const { sorted, sortKey, sortDir, toggleSort } = useTableSort(projects, 'number')

async function fetch() {
	const { data } = await projectsApi.index()
	projects.value = data.data
}

load(fetch)
</script>

<template>

	<Grid class="mb-10">

		<Span class="col-span-8 col-start-2">
			<PageTitle>Arbeiten</PageTitle>
		</Span>

		<Span class="col-span-8 col-start-2">
			<ListTable>

				<!-- Header -->
				<ListTableRow header>
					<ListTableCell span="col-span-1" first header sortable :sort-active="sortKey === 'number'" :sort-dir="sortDir" @sort="toggleSort('number')">Nr.</ListTableCell>
					<ListTableCell span="col-span-5" header sortable :sort-active="sortKey === 'title'" :sort-dir="sortDir" @sort="toggleSort('title')">Projektname</ListTableCell>
					<ListTableCell span="col-span-2" last header sortable :sort-active="sortKey === 'city'" :sort-dir="sortDir" @sort="toggleSort('city')">Ort</ListTableCell>
				</ListTableRow>

				<!-- Entries -->
				<ListTableRow v-for="project in sorted" :key="project.uuid" :to="{ name: 'projects.show', params: { id: project.uuid } }">
					<ListTableCell span="col-span-1" first>{{ project.number }}</ListTableCell>
					<ListTableCell span="col-span-5">{{ project.title }}</ListTableCell>
					<ListTableCell span="col-span-2" last>{{ project.city }}</ListTableCell>
				</ListTableRow>

			</ListTable>
		</Span>

	</Grid>

</template>
