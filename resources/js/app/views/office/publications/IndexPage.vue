<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import publicationsApi from '@/api/publications'
import { usePageLoader } from '@/composables/usePageLoader'
import { useConfirm } from '@/composables/useConfirm'
import { useCan } from '@/composables/useCan'
import draggable from 'vuedraggable'
import PageTitle from '@/components/ui/PageTitle.vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import Button from '@/components/ui/form/Button.vue'
import Burger from '@/components/icons/Burger.vue'
import Cross from '@/components/icons/Cross.vue'
import Plus from '@/components/icons/Plus.vue'
import TitleLightbox from '@/views/office/publications/components/TitleLightbox.vue'

const router = useRouter()
const { load } = usePageLoader()
const { confirm } = useConfirm()
const { canCreate, canReorder, canDelete } = useCan()

const publications = ref([])
const titleLightbox = ref(null)

async function fetch() {
	const { data } = await publicationsApi.index()
	publications.value = data.data
}

async function onCreated() {
	const { data } = await publicationsApi.index()
	publications.value = data.data
	const created = data.data[data.data.length - 1]
	if (created) {
		router.push({ name: 'publications.show', params: { id: created.uuid } })
	}
}

async function destroy(pub) {
	const ok = await confirm({
		message: 'Möchtest Du diese Publikation wirklich löschen?',
		confirmLabel: 'Löschen',
		variant: 'danger',
	})
	if (ok) {
		await publicationsApi.destroy(pub.uuid)
		await fetch()
	}
}

async function reorder() {
	const items = publications.value.map((p, i) => ({
		uuid: p.uuid,
		sort_order: i,
	}))
	await publicationsApi.reorder(items)
}

load(fetch)
</script>

<template>

	<!-- Header -->
	<Grid class="mb-40">
		<Span class="col-span-8 col-start-2">
			<PageTitle>Publikationen</PageTitle>
		</Span>
		<Span class="col-span-8 col-start-2">
			<template v-if="canCreate">
				<Button @click="titleLightbox.open()" class="px-20">
					<template #icon-right>
						<Plus class="w-10 h-10" />
					</template>
					Neue Publikation
				</Button>
			</template>
		</Span>
	</Grid>

	<!-- List -->
	<Grid>
		<draggable
			v-model="publications"
			:disabled="!canReorder"
			item-key="uuid"
			handle=".pub-drag-handle"
			ghost-class="opacity-50"
			animation="150"
			class="col-span-10 flex flex-col gap-20"
			@end="reorder">

			<template #item="{ element: pub }">
				<Span class="col-span-10">
					<Grid :cols="10">

						<Span class="col-span-1 flex items-center justify-end">
							<template v-if="canReorder">
								<Burger class="w-18 h-10 cursor-grab pub-drag-handle" />
							</template>
						</Span>

						<Span class="col-span-8">
							<button
								type="button"
								class="w-full bg-white text-lg font-semibold min-h-50 flex items-center px-20 cursor-pointer text-left"
								@click="router.push({ name: 'publications.show', params: { id: pub.uuid } })">
								{{ pub.title }}
							</button>
						</Span>

						<Span class="col-span-1 flex items-center justify-start">
							<template v-if="canDelete">
								<Cross class="w-10 cursor-pointer" @click="destroy(pub)" />
							</template>
						</Span>

					</Grid>
				</Span>
			</template>

		</draggable>
	</Grid>

	<TitleLightbox ref="titleLightbox" @created="onCreated" />

</template>
