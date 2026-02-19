<script setup>
import { ref, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import draggable from 'vuedraggable'
import projectsApi from '@/api/projects'
import { usePageLoader } from '@/composables/usePageLoader'
import { useMediaStore } from '@/stores/media'
import { useConfirm } from '@/composables/useConfirm'
import { useToast } from '@/composables/useToast'
import PageTitle from '@/components/ui/PageTitle.vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import Card from '@/components/ui/Card.vue'
import Arrow from '@/components/icons/Arrow.vue'
import MediaCard from '@/components/media/MediaCard.vue'
import NavBar from '@/components/ui/nav-bar/NavBar.vue'
import NavBarButton from '@/components/ui/nav-bar/NavBarButton.vue'
import Window from '@/components/icons/Window.vue'
import Download from '@/components/icons/Download.vue'
import List from '@/components/icons/List.vue'
import Eye from '@/components/icons/Eye.vue'
import FormContainer from '@/components/ui/form/FormContainer.vue'
import ActionBar from '@/components/ui/form/ActionBar.vue'
import Button from '@/components/ui/form/Button.vue'
import MediaUploader from '@/components/media/MediaUploader.vue'

const route = useRoute()
const router = useRouter()
const { load } = usePageLoader()
const mediaStore = useMediaStore()
const { confirm } = useConfirm()
const toast = useToast()

const project = ref(null)

const dragItems = computed({
	get: () => mediaStore.items,
	set: (value) => mediaStore.reorder(value),
})

async function fetch() {
	const { data } = await projectsApi.show(route.params.id)
	project.value = data.data
	mediaStore.setItems(data.data.media || [])
}

load(fetch)

function goBack() {
	router.push({ name: 'projects.show', params: { id: route.params.id } })
}

function onUploaded(media) {
	mediaStore.addItem(media)
}

async function onDelete(item) {
	const ok = await confirm({
		message: 'Möchtest Du dieses Bild wirklich löschen?',
		confirmLabel: 'Löschen',
		variant: 'danger',
	})
	if (!ok) return
	await mediaStore.deleteItem(item.uuid)
}

async function handleSubmit() {
	const tempMedia = mediaStore.tempItems.map(item => ({
		uuid: item.uuid,
		file: item.file,
		original_name: item.original_name,
		mime_type: item.mime_type,
		size: item.size,
		width: item.width,
		height: item.height,
		alt: item.alt || null,
		caption: item.caption || null,
		credits: item.credits || null,
	}))

	if (tempMedia.length) {
		await projectsApi.attachMedia(route.params.id, tempMedia)
	}

	toast.success('Bilder gespeichert')
	goBack()
}
</script>

<template>

	<!-- NavBar -->
	<Grid v-if="project" class="mb-40">
		<Span class="col-span-8 col-start-2">
			<NavBar>
				<NavBarButton>
					<template #icon>
						<Window class="w-14 h-auto" />
					</template>
					Web
				</NavBarButton>
				<NavBarButton>
					<template #icon>
						<Download class="w-14 h-auto" />
					</template>
					Rohdaten (ZIP)
				</NavBarButton>
				<NavBarButton>
					<template #icon>
						<List class="w-14 h-auto" />
					</template>
					Referenzblatt (PDF)
				</NavBarButton>
				<NavBarButton>
					<template #icon>
						<Eye class="w-14 h-auto" />
					</template>
					Publiziert (Website)
				</NavBarButton>
			</NavBar>
		</Span>
	</Grid>

	<!-- Header -->
	<Grid class="mb-20">
		<Span class="col-span-1 flex items-center justify-center">
			<button type="button" @click="goBack">
				<Arrow variant="left" class="w-25 cursor-pointer" />
			</button>
		</Span>
		<Span class="col-span-8">
			<PageTitle>
				{{ project?.full_title }}
			</PageTitle>
		</Span>
	</Grid>

	<!-- Content -->
	<FormContainer v-if="project" @submit="handleSubmit">
		<Grid class="mb-20">
			<Span class="col-span-8 col-start-2">
				<Card has-header>
					<Grid :cols="6">
						<Span class="col-span-8 font-semibold text-md min-h-50 flex items-center border-b-thin">
							<span>Bilder</span>
						</Span>
					</Grid>

					<draggable
						v-if="mediaStore.items.length"
						v-model="dragItems"
						item-key="uuid"
						handle=".drag-handle"
						class="grid grid-cols-2 lg:grid-cols-4 gap-20 pt-20"
						ghost-class="opacity-30"
						animation="150"
					>
						<template #item="{ element }">
							<MediaCard
								:item="element"
								:draggable="true"
								:deletable="true"
								:show-filename="true"
								@delete="onDelete" />
						</template>
					</draggable>

					<div class="pt-20">
						<MediaUploader @uploaded="onUploaded" />
					</div>

					<div class="grid grid-cols-2 gap-20 pt-20">
						<Button type="submit" class="text-center">Speichern</Button>
						<Button type="button" class="text-center" @click="goBack">Abbrechen</Button>
					</div>
				</Card>
			</Span>
		</Grid>

		<ActionBar @cancel="goBack" />
	</FormContainer>

</template>
