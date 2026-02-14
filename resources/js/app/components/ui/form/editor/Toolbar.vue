<script setup>
import { ref } from 'vue'
import Bold from '@/components/icons/Bold.vue'
import Underline from '@/components/icons/Underline.vue'
import Link from '@/components/icons/Link.vue'
import LinkDialog from './LinkDialog.vue'

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
				class="p-5 text-black hover:text-gray cursor-pointer transition-colors duration-100"
				title="Bold"
				@click="editor.chain().focus().toggleBold().run()">
				<Bold class="w-9 h-10" />
			</button>

			<button
				type="button"
				class="p-5 text-black hover:text-gray cursor-pointer transition-colors duration-100"
				title="Underline"
				@click="editor.chain().focus().toggleUnderline().run()">
				<Underline class="w-8 h-12" />
			</button>

			<button
				type="button"
				class="p-5 text-black hover:text-gray cursor-pointer transition-colors duration-100"
				title="Link"
				@click="showLinkDialog = true">
				<Link class="w-12 h-12" />
			</button>
		</div>

		<button
			type="button"
			class="text-xs text-gray hover:text-black cursor-pointer"
			@click="editor.chain().focus().unsetAllMarks().clearNodes().run()">
			Formatierung entfernen
		</button>

		<LinkDialog
			:open="showLinkDialog"
			:editor="editor"
			@close="showLinkDialog = false" />

	</div>
</template>
