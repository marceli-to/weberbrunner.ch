<script setup>
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import Button from '@/components/ui/form/Button.vue'
import Plus from '@/components/icons/Plus.vue'

defineEmits(['click'])
</script>

<template>
	<Grid :cols="10" class="mb-10">
		<Span class="col-span-8 col-start-2">
			<Button class="px-20" @click="$emit('click')">
				<template #icon-right>
					<Plus class="w-10 h-10" />
				</template>
				Neuer Eintrag
			</Button>
		</Span>
	</Grid>
</template>
