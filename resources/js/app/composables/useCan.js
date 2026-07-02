import { computed } from 'vue'
import { useAuthStore } from '@/stores/auth'

export function useCan() {
	const auth = useAuthStore()

	const role = computed(() => auth.user?.role ?? null)

	const isAdmin = computed(() => role.value === 'admin')
	const isEditor = computed(() => role.value === 'editor')
	const isViewer = computed(() => role.value === 'viewer')

	const canCreate = computed(() => isAdmin.value || isEditor.value)
	const canUpdate = computed(() => isAdmin.value || isEditor.value)
	const canReorder = computed(() => isAdmin.value || isEditor.value)
	const canUpload = computed(() => isAdmin.value || isEditor.value)
	const canDelete = computed(() => isAdmin.value)
	const canRestore = computed(() => isAdmin.value)
	const canPublish = computed(() => isAdmin.value)

	const canViewActivity = computed(() => isAdmin.value)
	const canViewSettings = computed(() => isAdmin.value || isEditor.value)

	return {
		role,
		isAdmin, isEditor, isViewer,
		canCreate, canUpdate, canDelete, canRestore, canReorder, canUpload, canPublish,
		canViewActivity, canViewSettings,
	}
}
