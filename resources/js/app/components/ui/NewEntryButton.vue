<script setup>
import Button from '@/components/ui/form/Button.vue'
import Plus from '@/components/icons/Plus.vue'

defineEmits(['click'])
</script>

<template>
	<Button class="px-20" @click="$emit('click')">
		<template #icon-right>
			<Plus class="w-10 h-10" />
		</template>
		<slot>Neuer Eintrag</slot>
	</Button>
</template>
