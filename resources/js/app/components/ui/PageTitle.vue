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
		<h1 class="text-lg font-semibold group/title flex items-center gap-10">
			<slot />
			<button v-if="editable" type="button" class="cursor-pointer" @click="$emit('edit')">
				<PencilCircle class="w-20 opacity-0 group-hover/title:opacity-100 transition-opacity" />
			</button>
		</h1>
		<a
			v-if="slug"
			:href="`/vorschau/${slug}`"
			target="_blank"
			aria-label="Vorschau auf der Website anzeigen">
			<LinkExternal class="w-24 h-auto" />
		</a>
	</div>
</template>
