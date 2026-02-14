<script setup>
import talksApi from '@/api/talks'
import SectionEntryForm from '@/components/ui/SectionEntryForm.vue'
import Span from '@/components/ui/grid/Span.vue'
import Input from '@/components/ui/form/Input.vue'

function buildPayload(form) {
	return { text: form.text, link: form.link || null }
}
</script>

<template>
	<SectionEntryForm
		:api="talksApi"
		page-title="Vorträge"
		back-route="office.talks"
		:extra-fields="{ link: '' }"
		:build-payload="buildPayload">
		<template #default="{ form, get, clear }">
			<Span class="col-span-8 col-start-2">
				<Input
					v-model="form.link"
					type="url"
					placeholder="Link"
					:error="get('link')"
					@focus="clear('link')" />
			</Span>
		</template>
	</SectionEntryForm>
</template>
