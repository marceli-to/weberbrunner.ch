<script setup>
import { ref } from 'vue'
import teamApi from '@/api/team'
import { usePageLoader } from '@/composables/usePageLoader'
import PageTitle from '@/components/ui/PageTitle.vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import ListTable from '@/components/ui/list/ListTable.vue'
import ListTableRow from '@/components/ui/list/ListTableRow.vue'
import ListTableCell from '@/components/ui/list/ListTableCell.vue'

const { load } = usePageLoader()
const members = ref([])

async function fetch() {
	const { data } = await teamApi.index()
	members.value = data.data
}

load(fetch)
</script>

<template>

	<Grid class="mb-10">

		<Span class="col-span-8 col-start-2">
			<PageTitle>Team</PageTitle>
		</Span>

		<Span class="col-span-8 col-start-2">
			<ListTable>

				<!-- Header -->
				<ListTableRow header>
					<ListTableCell span="col-span-2" first header>Nachname</ListTableCell>
					<ListTableCell span="col-span-2" header>Vorname</ListTableCell>
					<ListTableCell span="col-span-3" header>Ausbildung / Funktion</ListTableCell>
					<ListTableCell span="col-span-1" last header>Standort</ListTableCell>
				</ListTableRow>

				<!-- Entries -->
				<ListTableRow v-for="member in members" :key="member.uuid" :to="{ name: 'team.show', params: { id: member.uuid } }">
					<ListTableCell span="col-span-2" first>{{ member.name }}</ListTableCell>
					<ListTableCell span="col-span-2">{{ member.firstname }}</ListTableCell>
					<ListTableCell span="col-span-3">{{ member.title }}</ListTableCell>
					<ListTableCell span="col-span-1" last>{{ member.location?.title }}</ListTableCell>
				</ListTableRow>

			</ListTable>
		</Span>

	</Grid>

</template>
