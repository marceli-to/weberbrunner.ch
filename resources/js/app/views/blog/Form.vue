<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useBlogStore } from '../../stores/blog'
import { useMediaStore } from '../../stores/media'
import { useToast } from '../../composables/useToast'
import { useConfirm } from '../../composables/useConfirm'
import MediaUploader from '../../components/media/MediaUploader.vue'
import MediaGrid from '../../components/media/MediaGrid.vue'
import MediaEditModal from '../../components/media/MediaEditModal.vue'
import Editor from '../../components/ui/editor/Editor.vue'

const route = useRoute()
const router = useRouter()
const store = useBlogStore()
const mediaStore = useMediaStore()
const toast = useToast()
const { confirm } = useConfirm()

const isEdit = computed(() => !!route.params.id)
const editingMedia = ref(null)

const form = ref({
	title: '',
	content: '',
	publish: false,
})

onMounted(async () => {
	mediaStore.setItems([])
	if (isEdit.value) {
		await store.fetchPost(route.params.id)
		if (store.current) {
			form.value.title = store.current.title
			form.value.content = store.current.content || ''
			form.value.publish = store.current.publish
			mediaStore.setItems(store.current.media || [])
		}
	}
})

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
	}))

	const success = await store.savePost(form.value, isEdit.value ? route.params.id : null, tempMedia)
	if (success) {
		toast.success(isEdit.value ? 'Post updated' : 'Post created')
		router.push({ name: 'blog.index' })
	} else if (Object.keys(store.errors).length) {
		toast.error('Please check the form for errors')
	}
}

function onUploaded(media) {
	mediaStore.addItem(media)
}

function onEditMedia(media) {
	editingMedia.value = media
}

async function onSaveMedia({ uuid, data }) {
	const success = await mediaStore.updateItem(uuid, data)
	if (success) {
		editingMedia.value = null
	}
}

async function onDeleteMedia(media) {
	const ok = await confirm({
		message: 'Möchtest Du dieses Bild wirklich löschen?',
		confirmLabel: 'Löschen',
		variant: 'danger',
	})
	if (!ok) return
	await mediaStore.deleteItem(media.uuid)
}

function onReorderMedia(items) {
	mediaStore.reorder(items)
}

function onSetTeaser(media) {
	mediaStore.setTeaser(media.uuid)
}
</script>

<template>
	<div class="grid grid-cols-10 gap-x-20">
		<div class="col-span-9 flex items-center justify-between mb-20">
			<h2 class="text-lg font-semibold text-black">
				{{ isEdit ? 'Edit Post' : 'New Post' }}
			</h2>
		</div>

		<div v-if="store.loading" class="col-span-9 text-sm text-gray">
			Loading...
		</div>

		<form v-else class="col-span-9" @submit.prevent="handleSubmit">
			<div class="mb-16">
				<label class="block text-sm font-semibold text-black mb-4">Title</label>
				<input
					v-model="form.title"
					type="text"
					class="w-full border border-silver px-8 py-8 text-sm text-black focus:outline-none focus:border-black"
				/>
				<p v-if="store.errors.title" class="text-sm text-red mt-4">
					{{ store.errors.title[0] }}
				</p>
			</div>

			<div class="mb-16">
				<label class="block text-sm font-semibold text-black mb-4">Content</label>
				<Editor v-model="form.content" />
				<p v-if="store.errors.content" class="text-sm text-red mt-4">
					{{ store.errors.content[0] }}
				</p>
			</div>

			<div class="mb-24">
				<label class="flex items-center gap-8 text-sm text-black">
					<input
						v-model="form.publish"
						type="checkbox"
						class="w-12 h-12"
					/>
					Publish
				</label>
			</div>

			<div class="mb-24">
				<label class="block text-sm font-semibold text-black mb-8">Bilder</label>
				<MediaGrid
					v-if="mediaStore.items.length"
					:items="mediaStore.items"
					class="mb-12"
					@edit="onEditMedia"
					@delete="onDeleteMedia"
					@reorder="onReorderMedia"
					@teaser="onSetTeaser"
				/>
				<MediaUploader @uploaded="onUploaded" />
			</div>

			<div class="flex gap-12">
				<button
					type="submit"
					class="bg-black text-white text-sm font-semibold px-16 py-8"
				>
					{{ isEdit ? 'Update' : 'Create' }}
				</button>
				<button
					type="button"
					class="border border-black text-black text-sm font-semibold px-16 py-8"
					@click="router.push({ name: 'blog.index' })"
				>
					Cancel
				</button>
			</div>
		</form>

		<MediaEditModal
			:media="editingMedia"
			@close="editingMedia = null"
			@save="onSaveMedia" />
      
	</div>
</template>
