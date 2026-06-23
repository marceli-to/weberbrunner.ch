<script setup>
import { ref, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import draggable from 'vuedraggable'
import projectsApi from '@/api/projects'
import { useProject } from '@/composables/useProject'
import { useMediaStore } from '@/stores/media'
import { useConfirm } from '@/composables/useConfirm'
import { useToast } from '@/composables/useToast'
import { useCan } from '@/composables/useCan'
import PageTitle from '@/components/ui/PageTitle.vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import SectionTitle from '@/components/ui/SectionTitle.vue'
import BackButton from '@/components/ui/BackButton.vue'
import MediaCard from '@/components/media/MediaCard.vue'
import ProjectNavBar from '@/views/projects/components/ProjectTabs.vue'
import FormContainer from '@/components/ui/form/FormContainer.vue'
import ActionBar from '@/components/ui/form/ActionBar.vue'
import MediaUploader from '@/components/media/MediaUploader.vue'
import MediaEditModal from '@/components/media/MediaEditModal.vue'

const route = useRoute()
const router = useRouter()
const mediaStore = useMediaStore()
const { confirm } = useConfirm()
const toast = useToast()
const { canUpdate, canDelete, canReorder, canUpload } = useCan()

const { project, fetch } = useProject((data) => {
	mediaStore.setItems(data.media || [])
})

const editingMedia = ref(null)

const dragItems = computed({
	get: () => mediaStore.items,
	set: (value) => mediaStore.reorder(value),
})

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

async function onEditSave({ uuid, data }) {
	await mediaStore.updateItem(uuid, data)
	editingMedia.value = null
	toast.success('Bild gespeichert')
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
			<ProjectNavBar />
		</Span>
	</Grid>

	<!-- Header -->
	<Grid class="mb-20">
		<Span class="col-span-1 flex items-center justify-center">
			<BackButton @click="goBack" />
		</Span>
		<Span class="col-span-8">
			<PageTitle :slug="project?.slug">
				{{ project?.full_title }}
			</PageTitle>
		</Span>
	</Grid>

	<!-- Content -->
	<FormContainer v-if="project" @submit="handleSubmit">
		<Grid class="mb-20">
			<Span class="col-span-8 col-start-2">
				<SectionTitle>Bilder</SectionTitle>

				<draggable
					v-if="mediaStore.items.length"
					v-model="dragItems"
					item-key="uuid"
					handle=".drag-handle"
					:disabled="!canReorder"
					class="grid grid-cols-2 lg:grid-cols-4 gap-20 pt-20"
					ghost-class="opacity-30"
					animation="150"
				>
					<template #item="{ element }">
						<MediaCard
							:item="element"
							:draggable="canReorder"
							:deletable="canDelete"
							:editable="canUpdate"
							:show-filename="true"
							variant="dark"
							@delete="onDelete"
							@edit="editingMedia = $event" />
					</template>
				</draggable>

				<template v-if="canUpload">
					<div class="pt-20">
						<MediaUploader @uploaded="onUploaded" />
					</div>
				</template>

			</Span>
		</Grid>

		<ActionBar v-show="mediaStore.tempItems.length" @cancel="goBack" />
	</FormContainer>

	<MediaEditModal
		:media="editingMedia"
		@save="onEditSave"
		@close="editingMedia = null" />

</template>
