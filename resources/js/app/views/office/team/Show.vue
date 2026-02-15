<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import teamApi from '@/api/team'
import PageTitle from '@/components/ui/PageTitle.vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import Arrow from '@/components/icons/Arrow.vue'
import PencilCircle from '@/components/icons/PencilCircle.vue'

const route = useRoute()
const router = useRouter()
const member = ref(null)

onMounted(async () => {
	const { data } = await teamApi.show(route.params.id)
	member.value = data.data
})

function goBack() {
	router.push({ name: 'office.team' })
}
</script>

<template>
	<template v-if="member">

		<!-- Header -->
		<Grid class="mb-20">
			<Span class="col-span-1 flex items-center justify-center">
				<button type="button" @click="goBack">
					<Arrow variant="left" class="w-25 cursor-pointer" />
				</button>
			</Span>
			<Span class="col-span-8">
				<PageTitle>
          {{ member.fullname }}
        </PageTitle>
			</Span>
		</Grid>

		<!-- Content -->
		<Grid class="mb-20">

			<Span class="col-span-2 col-start-2">
				[Image]
			</Span>

			<Span class="col-span-6 flex flex-col gap-20">

				<!-- Steckbrief Website -->
				<div class="bg-white pb-20">
					<Grid :cols="6" class="px-20">
						<Span class="col-span-2 font-semibold text-md min-h-50 flex items-center border-b border-b-thin">
              Steckbrief Website
            </Span>
						<Span class="col-span-4 min-h-50 flex items-center justify-end border-b border-b-thin">
							<PencilCircle class="w-25" />
						</Span>
					</Grid>
					<div v-for="(row, i) in [
						{ label: 'Nachname', value: member.name },
						{ label: 'Vorname', value: member.firstname },
						{ label: 'Ausbildung / Funktion', value: member.title },
						{ label: 'Standort', value: member.location?.title },
						{ label: 'Mitarbeit seit', value: member.since },
						{ label: 'E-Mail-Adresse', value: member.email },
					]" :key="i">
						<Grid :cols="6" class="px-20 pt-6 text-md">
							<Span class="col-span-2 font-semibold pb-6 border-b border-b-gray">{{ row.label }}</Span>
							<Span class="col-span-4 pb-6 border-b border-b-gray">{{ row.value }}</Span>
						</Grid>
					</div>
				</div>

			</Span>
		</Grid>

	</template>
</template>
