<script setup>
import { ref } from 'vue'
import teamApi from '@/api/team'
import { usePageLoader } from '@/composables/usePageLoader'
import { useTableSort } from '@/composables/useTableSort'
import PageTitle from '@/components/ui/PageTitle.vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import ListTable from '@/components/ui/list/ListTable.vue'
import ListTableRow from '@/components/ui/list/ListTableRow.vue'
import ListTableCell from '@/components/ui/list/ListTableCell.vue'

const { load } = usePageLoader()
const members = ref([])
const { sorted, sortKey, sortDir, toggleSort } = useTableSort(members, 'name', 'asc', 'team')

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
					<ListTableCell span="col-span-2" first header sortable :sort-active="sortKey === 'name'" :sort-dir="sortDir" @sort="toggleSort('name')">Nachname</ListTableCell>
					<ListTableCell span="col-span-2" header sortable :sort-active="sortKey === 'firstname'" :sort-dir="sortDir" @sort="toggleSort('firstname')">Vorname</ListTableCell>
					<ListTableCell span="col-span-3" header sortable :sort-active="sortKey === 'title'" :sort-dir="sortDir" @sort="toggleSort('title')">Ausbildung / Funktion</ListTableCell>
					<ListTableCell span="col-span-1" last header sortable :sort-active="sortKey === 'location.title'" :sort-dir="sortDir" @sort="toggleSort('location.title')">Standort</ListTableCell>
				</ListTableRow>

				<!-- Entries -->
				<ListTableRow v-for="member in sorted" :key="member.uuid" :to="{ name: 'team.show', params: { id: member.uuid } }">
					<ListTableCell span="col-span-2" first>
            <span class="block truncate">{{ member.name }}</span>
          </ListTableCell>
					<ListTableCell span="col-span-2">
            <span class="block truncate">{{ member.firstname }}</span>
          </ListTableCell>
					<ListTableCell span="col-span-3">
            <span class="block truncate">{{ member.title }}</span>
          </ListTableCell>
					<ListTableCell span="col-span-1" last>
            <span class="block truncate">{{ member.location?.title }}</span>
          </ListTableCell>
				</ListTableRow>

			</ListTable>
		</Span>

	</Grid>

</template>
