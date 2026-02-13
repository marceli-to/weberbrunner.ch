<script setup>
import { ref, nextTick } from 'vue'

const props = defineProps({
	editor: { type: Object, required: true },
})

const showLinkInput = ref(false)
const linkUrl = ref('')
const linkInput = ref(null)

function openLinkInput() {
	linkUrl.value = props.editor.getAttributes('link').href || ''
	showLinkInput.value = true
	nextTick(() => linkInput.value?.focus())
}

function applyLink() {
	const url = linkUrl.value.trim()
	if (url) {
		props.editor.chain().focus().extendMarkRange('link').setLink({ href: url }).run()
	} else {
		props.editor.chain().focus().extendMarkRange('link').unsetLink().run()
	}
	closeLinkInput()
}

function removeLink() {
	props.editor.chain().focus().extendMarkRange('link').unsetLink().run()
	closeLinkInput()
}

function closeLinkInput() {
	showLinkInput.value = false
	linkUrl.value = ''
	props.editor.commands.focus()
}
</script>

<template>
	<div class="relative border border-black border-b-0 bg-black">

		<div class="flex items-center gap-4">

			<button
				type="button"
				class="p-6 transition-colors"
				:class="editor.isActive('bold') ? 'text-white bg-gray-dark' : 'text-silver hover:text-white'"
				title="Bold"
				@click="editor.chain().focus().toggleBold().run()">
				<!-- Bold icon -->
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="w-20 h-20">
					<path d="M6 4h8a4 4 0 0 1 4 4 4 4 0 0 1-4 4H6z" /><path d="M6 12h9a4 4 0 0 1 4 4 4 4 0 0 1-4 4H6z" />
				</svg>
			</button>

			<button
				type="button"
				class="p-6 transition-colors"
				:class="editor.isActive('bulletList') ? 'text-white bg-gray-dark' : 'text-silver hover:text-white'"
				title="Bullet list"
				@click="editor.chain().focus().toggleBulletList().run()">
				<!-- Bullet list icon -->
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-20 h-20">
					<line x1="8" y1="6" x2="21" y2="6" /><line x1="8" y1="12" x2="21" y2="12" /><line x1="8" y1="18" x2="21" y2="18" />
					<circle cx="4" cy="6" r="1" fill="currentColor" /><circle cx="4" cy="12" r="1" fill="currentColor" /><circle cx="4" cy="18" r="1" fill="currentColor" />
				</svg>
			</button>

			<button
				type="button"
				class="p-6 transition-colors"
				:class="editor.isActive('orderedList') ? 'text-white bg-gray-dark' : 'text-silver hover:text-white'"
				title="Ordered list"
				@click="editor.chain().focus().toggleOrderedList().run()">
				<!-- Ordered list icon -->
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-20 h-20">
					<line x1="10" y1="6" x2="21" y2="6" /><line x1="10" y1="12" x2="21" y2="12" /><line x1="10" y1="18" x2="21" y2="18" />
					<text x="2" y="8" font-size="8" font-weight="600" fill="currentColor" stroke="none" font-family="system-ui">1</text>
					<text x="2" y="14" font-size="8" font-weight="600" fill="currentColor" stroke="none" font-family="system-ui">2</text>
					<text x="2" y="20" font-size="8" font-weight="600" fill="currentColor" stroke="none" font-family="system-ui">3</text>
				</svg>
			</button>

			<button
				type="button"
				class="p-6 transition-colors"
				:class="editor.isActive('link') ? 'text-white bg-gray-dark' : 'text-silver hover:text-white'"
				title="Link"
				@click="openLinkInput">
				<!-- Link icon -->
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-20 h-20">
					<path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71" />
					<path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71" />
				</svg>
			</button>
		</div>

		<!-- Link input overlay -->
		<div
			v-if="showLinkInput"
			class="absolute left-10 right-20 top-40 z-10 flex items-center gap-4 w-1/2 border border-silver bg-white p-4">

			<input
				ref="linkInput"
				v-model="linkUrl"
				type="url"
				placeholder="https://..."
				class="flex-1 border border-silver px-6 py-4 text-xs text-black focus:outline-none focus:border-black"
				@keydown.enter.prevent="applyLink"
				@keydown.escape.prevent="closeLinkInput" />

			<button
				type="button"
				class="bg-black text-white text-xs font-semibold px-8 py-4"
				@click="applyLink">
				Apply
			</button>

			<button
				v-if="editor.isActive('link')"
				type="button"
				class="border border-silver text-black text-xs font-semibold px-8 py-4 hover:border-black"
				@click="removeLink">
				Remove
			</button>

			<button
				type="button"
				class="text-gray hover:text-black p-4"
				title="Cancel"
				@click="closeLinkInput">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-12 h-12">
					<line x1="18" y1="6" x2="6" y2="18" /><line x1="6" y1="6" x2="18" y2="18" />
				</svg>
			</button>
		</div>

	</div>
</template>
