<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import networkApi from '@/api/network'
import sectionsApi from '@/api/sections'
import { useCollapsed } from '@/composables/useCollapsed'
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
import CollapsibleHeader from '@/components/ui/CollapsibleHeader.vue'
import Cross from '@/components/icons/Cross.vue'
import Eye from '@/components/icons/Eye.vue'
import Pencil from '@/components/icons/Pencil.vue'
import Plus from '@/components/icons/Plus.vue'
import Lightbox from '@/components/ui/lightbox/Lightbox.vue'

const router = useRouter()
const groups = ref([])
const { collapsed, toggle: toggleSection } = useCollapsed('network')
const title = ref('')
const { confirm } = useConfirm()
const { get, clear, submit } = useFormErrors()
const { show, open, close } = useLightbox(() => {
	title.value = ''
	clear()
})

async function fetchEntries() {
	const { data } = await networkApi.index()
	groups.value = data.data
}

async function storeSection() {
	const ok = await submit(() => sectionsApi.store({ title: title.value, type: 'network' }))
	if (ok) {
		close()
		await fetchEntries()
		groups.value.unshift(groups.value.pop())
		await reorderSections()
	}
}

async function deleteSection(group) {
	const count = group.entries.length
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
		await fetchEntries()
	}
}

async function deleteEntry(entry) {
	const ok = await confirm({
		message: 'Möchtest Du diesen Eintrag wirklich löschen?',
		confirmLabel: 'Löschen',
		variant: 'danger',
	})
	if (ok) {
		await networkApi.destroy(entry.uuid)
		await fetchEntries()
	}
}

async function togglePublish(entry) {
	entry.publish = !entry.publish
	await networkApi.toggle(entry.uuid)
}

async function reorderSections() {
	const items = groups.value.map((g, i) => ({
		id: g.section.id,
		sort_order: i,
	}))
	await sectionsApi.reorder(items)
}

async function reorderEntries(group) {
	const items = group.entries.map((e, i) => ({
		id: e.id,
		sort_order: i,
		section_id: group.section.id,
	}))
	await networkApi.reorder(items)
}

onMounted(fetchEntries)
</script>

<template>

	<!-- Header -->
	<Grid class="mb-40">

		<Span class="col-span-8 col-start-2">
			<PageTitle>Netzwerk</PageTitle>
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

	<!-- Entries -->
	<Grid>

		<draggable
			v-model="groups"
			item-key="section.uuid"
			handle=".section-drag-handle"
			ghost-class="opacity-50"
			animation="150"
			class="col-span-10 flex flex-col gap-20"
			@end="reorderSections">

			<template #item="{ element: group }">

				<Span class="col-span-10">

					<Grid :cols="10">

						<!-- Section header -->
						<Span class="col-span-1 flex items-center justify-end">
							<Burger class="w-18 h-10 cursor-grab section-drag-handle" />
						</Span>

						<Span class="col-span-8">
							<CollapsibleHeader
								:title="group.section.title"
								:collapsed="collapsed.has(group.section.uuid)"
								@toggle="toggleSection(group.section.uuid)" />
						</Span>

						<Span class="col-span-1 flex items-center justify-start">
							<Cross class="w-10 cursor-pointer" @click="deleteSection(group)" />
						</Span>

						<!-- Entries -->
						<Span v-show="!collapsed.has(group.section.uuid)" class="col-span-10 col-start-1">
							<draggable
								v-model="group.entries"
								group="entries"
								item-key="uuid"
								handle=".entry-drag-handle"
								ghost-class="opacity-50"
								animation="150"
								class="flex flex-col gap-10 min-h-1"
								:class="{ 'mb-10': group.entries.length }"
								@change="reorderEntries(group)">
								<template #item="{ element: entry }">
									<Grid :cols="10">
										<Span class="col-span-1 flex items-center justify-end">
											<Burger variant="sm" class="w-18 h-10 cursor-grab entry-drag-handle" />
										</Span>
										<Span class="col-span-8">
											<div class="bg-white font-semibold min-h-30 border border-black flex justify-between items-center px-20 select-none" :class="{ 'opacity-50': !entry.publish }">
												<span>
													{{ entry.text_plain }}
												</span>
												<span class="flex gap-x-20">
													<Pencil class="w-14 cursor-pointer" @click="router.push({ name: 'network.edit', params: { id: entry.uuid } })" />
													<Eye :variant="entry.publish ? 'visible' : 'hidden'" class="w-14 cursor-pointer" @click="togglePublish(entry)" />
												</span>
											</div>
										</Span>
										<Span class="col-span-1 flex items-center justify-start">
											<Cross class="w-10 cursor-pointer" @click="deleteEntry(entry)" />
										</Span>
									</Grid>
								</template>
							</draggable>

							<Grid :cols="10" class="mb-10">
								<Span class="col-span-8 col-start-2">
									<Button class="px-20" @click="router.push({ name: 'network.create', query: { section: group.section.uuid } })">
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
