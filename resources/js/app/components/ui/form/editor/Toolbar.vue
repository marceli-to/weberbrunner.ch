<script setup>
import { ref } from 'vue'
import Bold from '@/components/icons/Bold.vue'
import Underline from '@/components/icons/Underline.vue'
import Link from '@/components/icons/Link.vue'
import H2 from '@/components/icons/H2.vue'
import H3 from '@/components/icons/H3.vue'
import UnorderedList from '@/components/icons/UnorderedList.vue'
import LinkDialog from '@/components/ui/form/editor/LinkDialog.vue'

const props = defineProps({
	editor: { type: Object, required: true },
})

const showLinkDialog = ref(false)
</script>

<template>
	<div class="relative border-thin border-solid border-black bg-white px-15 min-h-30 flex items-center justify-between">

		<div class="flex items-center gap-8">

			<button
				type="button"
				class="px-4 py-3 cursor-pointer transition-colors duration-100"
				:class="editor.isActive('heading', { level: 2 }) ? 'bg-navy text-white' : 'text-black hover:text-gray'"
				title="H2"
				@click="editor.chain().focus().toggleHeading({ level: 2 }).run()">
				<H2 class="w-16 h-11" />
			</button>

			<button
				type="button"
				class="px-4 py-3 cursor-pointer transition-colors duration-100"
				:class="editor.isActive('heading', { level: 3 }) ? 'bg-navy text-white' : 'text-black hover:text-gray'"
				title="H3"
				@click="editor.chain().focus().toggleHeading({ level: 3 }).run()">
				<H3 class="w-16 h-11" />
			</button>

			<button
				type="button"
				class="px-4 py-3 cursor-pointer transition-colors duration-100"
				:class="editor.isActive('bold') ? 'bg-navy text-white' : 'text-black hover:text-gray'"
				title="Bold"
				@click="editor.chain().focus().toggleBold().run()">
				<Bold class="w-9 h-10" />
			</button>

			<button
				type="button"
				class="px-4 py-3 cursor-pointer transition-colors duration-100"
				:class="editor.isActive('underline') ? 'bg-navy text-white' : 'text-black hover:text-gray'"
				title="Underline"
				@click="editor.chain().focus().toggleUnderline().run()">
				<Underline class="w-8 h-12" />
			</button>

			<button
				type="button"
				class="px-4 py-3 cursor-pointer transition-colors duration-100"
				:class="editor.isActive('bulletList') ? 'bg-navy text-white' : 'text-black hover:text-gray'"
				title="Liste"
				@click="editor.chain().focus().toggleBulletList().run()">
				<UnorderedList class="w-13 h-9" />
			</button>

			<button
				type="button"
				class="px-4 py-3 cursor-pointer transition-colors duration-100"
				:class="editor.isActive('link') ? 'bg-navy text-white' : 'text-black hover:text-gray'"
				title="Link"
				@click="showLinkDialog = true">
				<Link class="w-12 h-12" />
			</button>
		</div>

		<button
			type="button"
			class="px-4 py-3 text-xs text-gray hover:text-black cursor-pointer"
			@click="editor.chain().focus().unsetAllMarks().clearNodes().run()">
			Formatierung entfernen
		</button>

		<LinkDialog
			:open="showLinkDialog"
			:editor="editor"
			@close="showLinkDialog = false" />

	</div>
</template>
