<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import contactsApi from '@/api/contacts'
import { usePageLoader } from '@/composables/usePageLoader'
import { useCollapsed } from '@/composables/useCollapsed'
import { useConfirm } from '@/composables/useConfirm'
import PageTitle from '@/components/ui/PageTitle.vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import CollapsibleHeader from '@/components/ui/CollapsibleHeader.vue'
import EntryRow from '@/components/ui/EntryRow.vue'
import Cross from '@/components/icons/Cross.vue'
import NewEntryButton from '@/components/ui/NewEntryButton.vue'

const router = useRouter()
const { load } = usePageLoader()
const groups = ref([])
const { collapsed, toggle: toggleLocation } = useCollapsed('contacts')
const { confirm } = useConfirm()

async function fetch() {
	const { data } = await contactsApi.index()
	groups.value = data.data
}

async function destroy(contact) {
	const ok = await confirm({
		message: 'Möchtest Du diesen Eintrag wirklich löschen?',
		confirmLabel: 'Löschen',
		variant: 'danger',
	})
	if (ok) {
		await contactsApi.destroy(contact.uuid)
		await fetch()
	}
}

async function toggle(contact) {
	contact.publish = !contact.publish
	await contactsApi.toggle(contact.uuid)
}

load(fetch)
</script>

<template>

	<!-- Header -->
	<Grid class="mb-40">
		<Span class="col-span-8 col-start-2">
			<PageTitle>Kontakt</PageTitle>
		</Span>
	</Grid>

	<!-- Contacts -->
	<Grid>

		<div class="col-span-10 flex flex-col gap-20">

			<template v-for="group in groups" :key="group.location.uuid">

				<Span class="col-span-10">

					<Grid :cols="10">

						<!-- Location header -->
						<Span class="col-span-8 col-start-2">
							<CollapsibleHeader
								:title="group.location.title"
								:collapsed="collapsed.has(group.location.uuid)"
								@toggle="toggleLocation(group.location.uuid)" />
						</Span>

						<!-- Contact entry -->
						<Span v-show="!collapsed.has(group.location.uuid)" class="col-span-10 col-start-1">

							<Grid v-for="contact in group.contacts" :key="contact.uuid" :cols="10" class="mb-10">
								<Span class="col-span-8 col-start-2">
									<EntryRow
										:label="contact.company_name"
										:publish="contact.publish"
										@edit="router.push({ name: 'contacts.edit', params: { id: contact.uuid } })"
										@toggle-publish="toggle(contact)" />
								</Span>
								<Span class="col-span-1 flex items-center justify-start">
									<Cross class="w-10 cursor-pointer" @click="destroy(contact)" />
								</Span>
							</Grid>

							<NewEntryButton
								v-if="!group.contacts.length"
								@click="router.push({ name: 'contacts.create', query: { location: group.location.uuid } })" />

						</Span>

					</Grid>

				</Span>

			</template>

		</div>

	</Grid>

</template>
