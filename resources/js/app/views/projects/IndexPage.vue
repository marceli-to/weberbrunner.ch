<script setup>
import { ref } from 'vue'
import projectsApi from '@/api/projects'
import { usePageLoader } from '@/composables/usePageLoader'
import PageTitle from '@/components/ui/PageTitle.vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import ListTable from '@/components/ui/list/ListTable.vue'
import ListTableRow from '@/components/ui/list/ListTableRow.vue'
import ListTableCell from '@/components/ui/list/ListTableCell.vue'

const { load } = usePageLoader()
const projects = ref([])

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
					<ListTableCell span="col-span-1" first header>Nr.</ListTableCell>
					<ListTableCell span="col-span-5" header>Projektname</ListTableCell>
					<ListTableCell span="col-span-2" last header>Ort</ListTableCell>
				</ListTableRow>

				<!-- Entries -->
				<ListTableRow v-for="project in projects" :key="project.uuid" :to="{ name: 'projects.show', params: { id: project.uuid } }">
					<ListTableCell span="col-span-1" first>{{ project.number }}</ListTableCell>
					<ListTableCell span="col-span-5">{{ project.title }}</ListTableCell>
					<ListTableCell span="col-span-2" last>{{ project.city }}</ListTableCell>
				</ListTableRow>

			</ListTable>
		</Span>

	</Grid>

</template>
