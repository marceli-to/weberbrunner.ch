<script setup>
import { useProjectMeta } from '@/composables/useProjectMeta'
import { useCollapsed } from '@/composables/useCollapsed'
import { useCan } from '@/composables/useCan'
import WebLayout from '@/views/projects/components/Layout.vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import Card from '@/components/ui/Card.vue'
import CollapsibleHeader from '@/components/ui/CollapsibleHeader.vue'
import MediaCard from '@/components/media/MediaCard.vue'
import AddButton from '@/components/media/AddButton.vue'
import MediaPickerDrawer from '@/components/media/MediaPickerDrawer.vue'
import Textarea from '@/components/ui/form/Textarea.vue'
import Button from '@/components/ui/form/Button.vue'

const {
	project, ogImage, selectedOgImage, ogDrawerOpen,
	saveDescription, saveOgImage, removeOgImage,
} = useProjectMeta()
const { collapsed, toggle } = useCollapsed('project-meta')
const { canUpdate, canDelete } = useCan()
</script>

<template>
	<WebLayout :project="project">

		<!-- Meta Description -->
		<Grid class="mb-20">
			<Span class="col-span-8 col-start-2">
				<CollapsibleHeader
					:title="'Meta Description'"
					:collapsed="collapsed.has('meta')"
					@toggle="toggle('meta')" />
			</Span>
			<Span v-show="!collapsed.has('meta')" class="col-span-8 col-start-2">
				<Card>
					<form @submit.prevent="saveDescription">
						<Textarea v-model="project.meta_description" :disabled="!canUpdate" />
						<template v-if="canUpdate">
							<div class="flex gap-20 mt-10">
								<Button type="submit" class="flex justify-center">Speichern</Button>
							</div>
						</template>
					</form>
				</Card>
			</Span>
		</Grid>

		<!-- Open Graph Image -->
		<Grid class="mb-20">
			<Span class="col-span-8 col-start-2">
				<CollapsibleHeader
					:title="'Open Graph Image'"
					:collapsed="collapsed.has('og')"
					@toggle="toggle('og')" />
			</Span>
			<Span v-show="!collapsed.has('og')" class="col-span-2 col-start-2">
				<div v-if="ogImage">
					<MediaCard :item="ogImage" :deletable="canDelete" :editable="canUpdate" @delete="removeOgImage" @edit="ogDrawerOpen = true" />
				</div>
				<AddButton v-else-if="canUpdate" @click="ogDrawerOpen = true" />
			</Span>
		</Grid>

		<MediaPickerDrawer
			:open="ogDrawerOpen"
			:items="project.media"
			v-model="selectedOgImage"
			@close="ogDrawerOpen = false"
			@submit="saveOgImage" />

	</WebLayout>
</template>
