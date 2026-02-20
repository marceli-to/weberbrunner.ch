<script setup>
import { ref, watch } from 'vue'
import AppDialog from '@/components/ui/dialog/AppDialog.vue'
import Button from '@/components/ui/form/Button.vue'
import LinkFormFields from '@/components/ui/form/LinkFormFields.vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'

const props = defineProps({
	open: { type: Boolean, default: false },
	editor: { type: Object, required: true },
})

const emit = defineEmits(['close'])

const formRef = ref(null)
const initialMode = ref('external')
const initialUrl = ref('')
const initialTitle = ref('')
const initialProjectId = ref(null)
const initialNewTab = ref(false)

watch(() => props.open, async (val) => {
	if (!val) return

	initialMode.value = 'external'
	initialUrl.value = ''
	initialTitle.value = ''
	initialProjectId.value = null
	initialNewTab.value = false

	const attrs = props.editor.getAttributes('link')
	if (attrs.href) {
		initialUrl.value = attrs.href
		initialTitle.value = attrs.title || ''
		initialNewTab.value = attrs.target === '_blank'

		// Wait for projects to load, then check if href matches an internal project
		await new Promise(r => setTimeout(r, 0))
		if (formRef.value) {
			const match = formRef.value.projects.find(p => attrs.href === `/arbeiten/${p.slug}`)
			if (match) {
				initialMode.value = 'internal'
				formRef.value.setSelectedProject(match)
			}
		}
	}
})

function apply() {
	if (!formRef.value.validate()) return
	const data = formRef.value.getFormData()
	let href
	if (data.mode === 'internal' && data.selectedProject) {
		href = `/arbeiten/${data.selectedProject.slug}`
	} else {
		href = data.url.trim()
	}
	if (!href) return

	const attrs = { href }
	if (data.title.trim()) attrs.title = data.title.trim()
	attrs.target = data.newTab ? '_blank' : null

	props.editor.chain().focus().extendMarkRange('link').setLink(attrs).run()
	emit('close')
}

function remove() {
	props.editor.chain().focus().extendMarkRange('link').unsetLink().run()
	emit('close')
}
</script>

<template>
	<AppDialog :open="open" title="Link" @close="emit('close')">

		<LinkFormFields
			ref="formRef"
			:mode="initialMode"
			:url="initialUrl"
			:title="initialTitle"
			:selected-project-id="initialProjectId"
			:new-tab="initialNewTab"
			title-optional />

		<template #footer>
			<Grid :cols="2">
				<Span><Button class="justify-center" @click="apply">Übernehmen</Button></Span>
				<Span><Button class="justify-center" @click="emit('close')">Abbrechen</Button></Span>
			</Grid>
		</template>

	</AppDialog>
</template>
