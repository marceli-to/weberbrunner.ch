<script setup>
import { ref, computed } from 'vue'
import { useFormErrors } from '@/composables/useFormErrors'
import { useLightbox } from '@/composables/useLightbox'
import Lightbox from '@/components/ui/lightbox/Lightbox.vue'
import Grid from '@/components/ui/grid/Grid.vue'
import Span from '@/components/ui/grid/Span.vue'
import Button from '@/components/ui/form/Button.vue'
import Input from '@/components/ui/form/Input.vue'
import Select from '@/components/ui/form/Select.vue'

const props = defineProps({
	storeFn: Function,
	updateFn: Function,
	teamMembers: {
		type: Array,
		default: () => [],
	},
	linkedTeamMemberIds: {
		type: Array,
		default: () => [],
	},
})

const emit = defineEmits(['stored', 'updated'])

const roleOptions = [
	{ value: 'admin', label: 'Publizierende' },
	{ value: 'editor', label: 'Autor:innen' },
	{ value: 'viewer', label: 'Lesende' },
]

const editingItem = ref(null)
const type = ref('intern')
const teamMemberId = ref('')
const firstname = ref('')
const name = ref('')
const email = ref('')
const password = ref('')
const role = ref('viewer')

const { get, clear, submit } = useFormErrors()
const { show, open: openLightbox, close } = useLightbox(() => {
	editingItem.value = null
	type.value = 'intern'
	teamMemberId.value = ''
	firstname.value = ''
	name.value = ''
	email.value = ''
	password.value = ''
	role.value = 'viewer'
	clear()
})

const lightboxTitle = computed(() =>
	editingItem.value ? 'Benutzer*in bearbeiten' : 'Neue*r Benutzer*in'
)

const teamMemberOptions = computed(() => {
	const currentId = editingItem.value?.team_member_id ?? null
	const options = props.teamMembers
		.filter((m) => !props.linkedTeamMemberIds.includes(m.id) || m.id === currentId)
		.map((m) => ({ value: m.id, label: m.fullname }))
	return [{ value: '', label: 'Teammitglied wählen …' }, ...options]
})

function setType(value) {
	type.value = value
	clear()
}

function open() {
	openLightbox()
}

function edit(user) {
	openLightbox()
	editingItem.value = user
	type.value = user.type
	teamMemberId.value = user.team_member_id ?? ''
	firstname.value = user.firstname ?? ''
	name.value = user.name ?? ''
	email.value = user.email ?? ''
	role.value = user.role
}

async function store() {
	const payload =
		type.value === 'intern'
			? { type: 'intern', role: role.value, team_member_id: teamMemberId.value }
			: { type: 'extern', role: role.value, firstname: firstname.value, name: name.value, email: email.value }

	if (password.value) {
		payload.password = password.value
	}

	const isUpdate = !!editingItem.value
	const fn = isUpdate
		? () => props.updateFn(editingItem.value.uuid, payload)
		: () => props.storeFn(payload)

	const ok = await submit(fn)
	if (ok) {
		close()
		emit(isUpdate ? 'updated' : 'stored')
	}
}

defineExpose({ open, edit })
</script>

<template>
	<Lightbox :open="show" :title="lightboxTitle" @close="close" :closeable="false">
		<form @submit.prevent="store" class="px-20 flex flex-col gap-20">

			<Grid :cols="8" class="bg-navy p-10">
				<Span class="col-span-4">
					<Button
						type="button"
						:variant="type === 'intern' ? 'toggle-active' : 'toggle'"
						class="justify-center"
						@click="setType('intern')">
						Intern
					</Button>
				</Span>
				<Span class="col-span-4">
					<Button
						type="button"
						:variant="type === 'extern' ? 'toggle-active' : 'toggle'"
						class="justify-center"
						@click="setType('extern')">
						Extern
					</Button>
				</Span>
			</Grid>

			<template v-if="type === 'intern'">
				<Select
					v-model="teamMemberId"
					:options="teamMemberOptions"
					:error="get('team_member_id')" />
			</template>

			<template v-else>
				<Input
					v-model="firstname"
					placeholder="Vorname"
					:error="get('firstname')"
					@focus="clear('firstname')" />
				<Input
					v-model="name"
					placeholder="Name"
					:error="get('name')"
					@focus="clear('name')" />
				<Input
					v-model="email"
					type="email"
					placeholder="E-Mail"
					:error="get('email')"
					@focus="clear('email')" />
			</template>

			<Select
				v-model="role"
				:options="roleOptions"
				:error="get('role')" />

			<Input
				v-model="password"
				type="password"
				:placeholder="editingItem ? 'Passwort (leer lassen zum Beibehalten)' : 'Passwort'"
				:error="get('password')"
				@focus="clear('password')" />

			<div class="flex gap-20">
				<Button type="submit" class="flex justify-center">Speichern</Button>
				<Button type="button" class="flex justify-center" @click="close">Abbrechen</Button>
			</div>

		</form>
	</Lightbox>
</template>
