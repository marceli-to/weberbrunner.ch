<script setup>
import Eye from '@/components/icons/Eye.vue'
import Pencil from '@/components/icons/Pencil.vue'
import Star from '@/components/icons/Star.vue'

defineProps({
	label: String,
	sublabel: String,
	split: {
		type: Boolean,
		default: false,
	},
	publish: Boolean,
	showPublish: {
		type: Boolean,
		default: true,
	},
	editable: {
		type: Boolean,
		default: true,
	},
	standard: Boolean,
	showDefault: {
		type: Boolean,
		default: false,
	},
})

defineEmits(['edit', 'toggle-publish', 'toggle-default'])
</script>

<template>
	<template v-if="split">
		<div class="grid grid-cols-12 gap-x-10 select-none" :class="{ 'opacity-50': showPublish && !publish }">
			<div class="col-span-4 bg-white text-md min-h-30 border-thin border-black flex items-center px-20 flex-1">
				<span>{{ label }}</span>
			</div>
			<div class="col-span-8 bg-white text-md min-h-30 border-thin border-black flex justify-between items-center px-20 flex-1">
				<span>{{ sublabel }}</span>
				<span class="flex items-center gap-x-20">
					<Star v-if="showDefault" :variant="standard ? 'filled' : 'outline'" class="w-14 cursor-pointer" @click="$emit('toggle-default')" />
					<Pencil v-if="editable" class="w-14 cursor-pointer" @click="$emit('edit')" />
          <Eye v-if="showPublish" :variant="publish ? 'visible' : 'hidden'" class="w-14 cursor-pointer" @click="$emit('toggle-publish')" />
				</span>
			</div>
		</div>
	</template>
	<template v-else>
		<div
			class="bg-white text-md min-h-30 border-thin border-black flex justify-between items-center px-20 select-none"
			:class="{ 'opacity-50': showPublish && !publish }">
			<span>{{ label }}</span>
			<span class="flex gap-x-20">
				<Pencil v-if="editable" class="w-14 cursor-pointer" @click="$emit('edit')" />
				<Star v-if="showDefault" :variant="standard ? 'filled' : 'outline'" class="w-14 cursor-pointer" @click="$emit('toggle-default')" />
				<Eye v-if="showPublish" :variant="publish ? 'visible' : 'hidden'" class="w-14 cursor-pointer" @click="$emit('toggle-publish')" />
			</span>
		</div>
	</template>
</template>
