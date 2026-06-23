<script setup>
import { watch } from 'vue'
import { useEditor, EditorContent } from '@tiptap/vue-3'
import StarterKit from '@tiptap/starter-kit'
import Link from '@tiptap/extension-link'
import Toolbar from '@/components/ui/form/editor/Toolbar.vue'

const props = defineProps({
	modelValue: { type: String, default: '' },
	error: { type: String, default: null },
	editable: { type: Boolean, default: true },
})

const emit = defineEmits(['update:modelValue', 'focus'])

const editor = useEditor({
	content: props.modelValue,
	editable: props.editable,
	extensions: [
		StarterKit.configure({
			heading: {
				levels: [2, 3],
			},
			codeBlock: false,
			blockquote: false,
			code: false,
			horizontalRule: false,
			link: false,
		}),
		Link.configure({
			openOnClick: false,
			HTMLAttributes: {
				target: null,
			},
		}),
	],
	onUpdate({ editor }) {
		emit('update:modelValue', editor.getHTML())
	},
	onFocus() {
		emit('focus')
	},
})

watch(() => props.modelValue, (value) => {
	if (!editor.value) return
	if (editor.value.getHTML() === value) return
	editor.value.commands.setContent(value, false)
})

watch(() => props.editable, (value) => {
	if (!editor.value) return
	editor.value.setEditable(value)
})
</script>

<template>
	<div class="editor" :class="{ 'has-error': error }">
		<Toolbar v-if="editor && editable" :editor="editor" />
		<EditorContent :editor="editor" />
	</div>
</template>
