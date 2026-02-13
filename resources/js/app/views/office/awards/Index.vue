<script setup>
import { ref } from 'vue'
import PageTitle from '@/components/ui/PageTitle.vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import Button from '@/components/ui/form/Button.vue'
import Plus from '@/components/icons/Plus.vue'
import Lightbox from '@/components/ui/lightbox/Lightbox.vue'

const showLightbox = ref(false)
</script>

<template>

	<Grid class="mb-20">

		<Span class="col-span-8 col-start-2">
			<PageTitle>Auszeichnungen</PageTitle>
		</Span>

		<Span class="col-span-8 col-start-2">
			<Button @click="showLightbox = true" class="form-button">
				<template #icon-left>
					<Plus class="w-10 h-10" />
				</template>
				Neue Auszeichnung
			</Button>
		</Span>

	</Grid>

	<Lightbox :open="showLightbox" title="Neue Auszeichnung" @close="showLightbox = false">
		<p>Lightbox content</p>
	</Lightbox>

</template>
