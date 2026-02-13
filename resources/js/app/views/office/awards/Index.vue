<script setup>
import { ref, onMounted } from 'vue'
import awardsApi from '@/api/awards'
import sectionsApi from '@/api/sections'
import { useConfirm } from '@/composables/useConfirm'
import { useFormErrors } from '@/composables/useFormErrors'
import { useLightbox } from '@/composables/useLightbox'
import draggable from 'vuedraggable'
import PageTitle from '@/components/ui/PageTitle.vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import Button from '@/components/ui/form/Button.vue'
import Input from '@/components/ui/form/Input.vue'
import Burger from '@/components/icons/Burger.vue'
import Chevron from '@/components/icons/Chevron.vue'
import Cross from '@/components/icons/Cross.vue'
import Eye from '@/components/icons/Eye.vue'
import Pencil from '@/components/icons/Pencil.vue'
import Plus from '@/components/icons/Plus.vue'
import Lightbox from '@/components/ui/lightbox/Lightbox.vue'

const groups = ref([])
const title = ref('')
const { confirm } = useConfirm()
const { get, clear, submit } = useFormErrors()
const { show, open, close } = useLightbox(() => {
	title.value = ''
	clear()
})

async function fetchAwards() {
	const { data } = await awardsApi.index()
	groups.value = data.data
}

async function storeSection() {
	const ok = await submit(() => sectionsApi.store({ title: title.value, type: 'award' }))
	if (ok) {
		close()
		await fetchAwards()
	}
}

async function deleteSection(group) {
	const count = group.awards.length
	const message = count
		? `Möchtest Du die Kategorie «${group.section.title}» wirklich löschen? Alle ${count} Einträge werden ebenfalls gelöscht.`
		: `Möchtest Du die Kategorie «${group.section.title}» wirklich löschen?`
	const ok = await confirm({
		message,
		confirmLabel: 'Löschen',
		variant: 'danger',
	})
	if (ok) {
		await sectionsApi.destroy(group.section.uuid)
		await fetchAwards()
	}
}

async function deleteAward(award) {
	const ok = await confirm({
		message: 'Möchtest Du diesen Eintrag wirklich löschen?',
		confirmLabel: 'Löschen',
		variant: 'danger',
	})
	if (ok) {
		await awardsApi.destroy(award.uuid)
		await fetchAwards()
	}
}

async function reorderSections() {
	const items = groups.value.map((g, i) => ({
		id: g.section.id,
		sort_order: i,
	}))
	await sectionsApi.reorder(items)
}

async function reorderAwards(group) {
	const items = group.awards.map((a, i) => ({
		id: a.id,
		sort_order: i,
		section_id: group.section.id,
	}))
	await awardsApi.reorder(items)
}

onMounted(fetchAwards)
</script>

<template>

	<!-- Header -->
	<Grid class="mb-40">

		<Span class="col-span-8 col-start-2">
			<PageTitle>Auszeichnungen</PageTitle>
		</Span>

		<Span class="col-span-8 col-start-2">
			<Button @click="open" class="px-20">
				<template #icon-right>
					<Plus class="w-10 h-10" />
				</template>
				Neue Kategorie
			</Button>
		</Span>
	</Grid>

	<!-- Awards -->
	<Grid>

		<draggable
			v-model="groups"
			item-key="section.uuid"
			handle=".section-drag-handle"
			ghost-class="opacity-50"
			animation="150"
			class="col-span-10 flex flex-col gap-20"
			@end="reorderSections"
		>
			<template #item="{ element: group }">
				<Span class="col-span-10">

					<Grid :cols="10">

						<!-- Award section header -->
						<Span class="col-span-1 flex items-center justify-end">
							<Burger class="w-18 h-10 cursor-grab section-drag-handle" />
						</Span>

						<Span class="col-span-8">
							<div class="bg-white text-lg font-semibold min-h-50 flex justify-between items-center px-20">
								<span>
									{{ group.section.title }}
								</span>
								<Chevron variant="up" size="lg" class="w-20" />
							</div>
						</Span>

						<Span class="col-span-1 flex items-center justify-start">
							<Cross class="w-10 cursor-pointer" @click="deleteSection(group)" />
						</Span>

						<!-- Award entries -->
						<Span class="col-span-10 col-start-1">
							<draggable
								v-model="group.awards"
								group="awards"
								item-key="uuid"
								handle=".award-drag-handle"
								ghost-class="opacity-50"
								animation="150"
								class="flex flex-col gap-10 mb-20 min-h-20"
								@change="reorderAwards(group)"
							>
								<template #item="{ element: award }">
									<Grid :cols="10">
										<Span class="col-span-1 flex items-center justify-end">
											<Burger variant="sm" class="w-18 h-10 cursor-grab award-drag-handle" />
										</Span>
										<Span class="col-span-8">
											<div class="bg-white font-semibold min-h-30 border border-black flex justify-between items-center px-20">
												<span>
													{{ award.text_plain }}
												</span>
												<span class="flex gap-x-20">
													<Pencil class="w-14 cursor-pointer" />
													<Eye class="w-14 cursor-pointer" />
												</span>
											</div>
										</Span>
										<Span class="col-span-1 flex items-center justify-start">
											<Cross class="w-10 cursor-pointer" @click="deleteAward(award)" />
										</Span>
									</Grid>
								</template>
							</draggable>
						</Span>

					</Grid>
				</Span>
			</template>
		</draggable>

	</Grid>

	<!-- Lightbox -->
	<Lightbox :open="show" title="Neue Kategorie" @close="close" :closeable="false">
		<form @submit.prevent="storeSection">
			<Input v-model="title" :error="get('title')" placeholder="Bezeichnung" class="form-input form-input--lg" @focus="clear('title')" />
			<div class="flex gap-20 mt-24">
				<Button type="submit" class="flex justify-center">Speichern</Button>
				<Button @click="close" class="flex justify-center">Abbrechen</Button>
			</div>
		</form>
	</Lightbox>

</template>
