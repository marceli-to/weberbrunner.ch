<script setup>
import LinkExternal from '@/components/icons/LinkExternal.vue'
import PencilCircle from '@/components/icons/PencilCircle.vue'

defineProps({
	slug: {
		type: String,
		default: null,
	},
	editable: {
		type: Boolean,
		default: false,
	},
})

defineEmits(['edit'])
</script>

<template>
	<div class="bg-white min-h-50 flex items-center px-20" :class="{ 'justify-between': slug || editable }">
		<h1 class="text-lg font-semibold">
			<slot />
		</h1>
		<button v-if="editable" type="button" class="cursor-pointer" @click="$emit('edit')">
			<PencilCircle class="w-25" />
		</button>
		<a
			v-else-if="slug"
			:href="`/vorschau/${slug}`"
			target="_blank"
			aria-label="Projekt auf der Website anzeigen">
			<LinkExternal class="w-24 h-auto" />
		</a>
	</div>
</template>
