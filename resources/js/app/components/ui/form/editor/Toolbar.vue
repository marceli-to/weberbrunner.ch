<script setup>
import { ref, nextTick } from 'vue'
import Bold from '@/components/icons/Bold.vue'
import Underline from '@/components/icons/Underline.vue'
import Link from '@/components/icons/Link.vue'
import Cross from '@/components/icons/Cross.vue'

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
	<div class="relative border-thin border-solid border-black bg-white px-15 min-h-30 flex items-center">

		<div class="flex items-center gap-8">

			<button
				type="button"
				class="p-5 text-black"
				title="Bold"
				@click="editor.chain().focus().toggleBold().run()">
				<Bold class="w-9 h-10" />
			</button>

			<button
				type="button"
				class="p-5 text-black"
				title="Underline"
				@click="editor.chain().focus().toggleUnderline().run()">
				<Underline class="w-8 h-12" />
			</button>

			<button
				type="button"
				class="p-5 text-black"
				title="Link"
				@click="openLinkInput">
				<Link class="w-12 h-12" />
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
				<Cross class="w-12 h-12" />
			</button>
		</div>

	</div>
</template>
