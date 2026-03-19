<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'
import Uppy from '@uppy/core'
import StatusBar from '@uppy/status-bar'
import XHRUpload from '@uppy/xhr-upload'
import German from '@uppy/locales/lib/de_DE'

import PlusCircle from '@/components/icons/PlusCircle.vue'

import '@uppy/core/css/style.min.css'
import '@uppy/status-bar/css/style.min.css'

const props = defineProps({
	showButtons: { type: Boolean, default: false },
	allowedFileTypes: { type: Array, default: () => ['.jpg', '.jpeg', '.png', '.webp', '.gif'] },
})

const emit = defineEmits(['uploaded', 'save', 'cancel'])

const statusBarRef = ref(null)
const fileInputRef = ref(null)
const isDragging = ref(false)
let dragCounter = 0
let uppy = null

function addFiles(files) {
	for (const file of files) {
		try {
			uppy.addFile({
				name: file.name,
				type: file.type,
				data: file,
				source: 'custom-drop-zone',
			})
		} catch (err) {
			// Uppy throws on duplicate/invalid files — ignore
		}
	}
}

function onDragEnter(e) {
	e.preventDefault()
	dragCounter++
	isDragging.value = true
}

function onDragOver(e) {
	e.preventDefault()
}

function onDragLeave() {
	dragCounter--
	if (dragCounter === 0) {
		isDragging.value = false
	}
}

function onDrop(e) {
	e.preventDefault()
	dragCounter = 0
	isDragging.value = false
	if (e.dataTransfer?.files?.length) {
		addFiles(e.dataTransfer.files)
	}
}

function onBrowse() {
	fileInputRef.value?.click()
}

function onFileChange(e) {
	if (e.target.files?.length) {
		addFiles(e.target.files)
		e.target.value = ''
	}
}

onMounted(() => {
	const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content

	uppy = new Uppy({
		locale: German,
		autoProceed: true,
		restrictions: {
			allowedFileTypes: props.allowedFileTypes,
			maxFileSize: 51200 * 1024,
		},
	})

	uppy.use(StatusBar, {
		target: statusBarRef.value,
		hideUploadButton: true,
		hideAfterFinish: false,
	})

	uppy.use(XHRUpload, {
		endpoint: '/api/dashboard/media/upload',
		fieldName: 'file',
		headers: {
			'X-CSRF-TOKEN': csrfToken,
			'Accept': 'application/json',
			'X-Requested-With': 'XMLHttpRequest',
		},
	})

	uppy.on('upload-success', (file, response) => {
		emit('uploaded', response.body.data)
		uppy.removeFile(file.id)
	})
})

onBeforeUnmount(() => {
	if (uppy) {
		uppy.destroy()
	}
})
</script>

<template>
	<div class="media-uploader">
		<div class="media-uploader__label">
			<button type="button" class="media-uploader__browse" @click="onBrowse">Drag-and-drop / Durchsuchen</button>
		</div>
		<div
			class="media-uploader__dropzone"
			:class="{ 'media-uploader__dropzone--dragging': isDragging }"
			@dragenter="onDragEnter"
			@dragover="onDragOver"
			@dragleave="onDragLeave"
			@drop="onDrop"
			@click="onBrowse"
		>
			<div class="media-uploader__icon">
				<PlusCircle class="w-25 h-auto" />
			</div>
			<input
				ref="fileInputRef"
				type="file"
				multiple
				:accept="props.allowedFileTypes.join(',')"
				class="hidden"
				@change="onFileChange"
			/>
		</div>
		<div ref="statusBarRef"></div>
		<div v-if="showButtons" class="media-uploader__buttons">
			<button type="button" class="media-uploader__btn" @click="$emit('save')">Speichern</button>
			<button type="button" class="media-uploader__btn" @click="$emit('cancel')">Abbrechen</button>
		</div>
	</div>
</template>
