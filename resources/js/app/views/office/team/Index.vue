<script setup>
import { onMounted } from 'vue'
import { useTeamStore } from '@/stores/team'
import PageTitle from '@/components/ui/PageTitle.vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import ListTable from '@/components/ui/list/ListTable.vue'
import ListTableRow from '@/components/ui/list/ListTableRow.vue'
import ListTableCell from '@/components/ui/list/ListTableCell.vue'

const teamStore = useTeamStore()

onMounted(() => {
	teamStore.fetchMembers()
})
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
					<ListTableCell :span="2" first header>Nachname</ListTableCell>
					<ListTableCell :span="2" header>Vorname</ListTableCell>
					<ListTableCell :span="3" header>Ausbildung / Funktion</ListTableCell>
					<ListTableCell :span="1" last header>Standort</ListTableCell>
				</ListTableRow>

				<!-- Entries -->
				<ListTableRow v-for="member in teamStore.members" :key="member.uuid">
					<ListTableCell :span="2" first>{{ member.name }}</ListTableCell>
					<ListTableCell :span="2">{{ member.firstname }}</ListTableCell>
					<ListTableCell :span="3">{{ member.title }}</ListTableCell>
					<ListTableCell :span="1" last>{{ member.location?.title }}</ListTableCell>
				</ListTableRow>

			</ListTable>
		</Span>

	</Grid>

</template>
