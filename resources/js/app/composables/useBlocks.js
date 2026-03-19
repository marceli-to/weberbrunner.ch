import { ref, watch } from 'vue'
import { useToast } from '@/composables/useToast'
import { useConfirm } from '@/composables/useConfirm'

export function useBlocks(api, parentUuidFn, emit, { filterFn } = {}) {
	const toast = useToast()
	const { confirm } = useConfirm()
	const blockTitleForm = ref(null)
	const blocks = ref([])
	const lastCreatedUuid = ref(null)
	const pendingType = ref(null)

	function watchBlocks(getter) {
		watch(getter, (val) => {
			blocks.value = filterFn ? (val || []).filter(filterFn) : (val || [])
		}, { immediate: true })
	}

	function addBlock(type) {
		pendingType.value = type
		blockTitleForm.value.open()
	}

	async function blockStoreFn(title) {
		const response = await api.store(parentUuidFn(), { type: pendingType.value, title })
		lastCreatedUuid.value = response.data.data.uuid
		return response
	}

	function blockUpdateFn(uuid, title) {
		return api.update(parentUuidFn(), uuid, { title })
	}

	function onBlockStored() {
		emit('updated')
		toast.success('Block hinzugefügt')
	}

	async function updateBlock(block, data) {
		await api.update(parentUuidFn(), block.uuid, data)
		emit('updated')
		toast.success('Block gespeichert')
	}

	async function deleteBlock(block) {
		const ok = await confirm({
			message: 'Möchtest Du diesen Block wirklich löschen?',
			confirmLabel: 'Löschen',
			variant: 'danger',
		})
		if (!ok) return
		await api.destroy(parentUuidFn(), block.uuid)
		emit('updated')
		toast.success('Block gelöscht')
	}

	async function reorderBlocks() {
		const items = blocks.value.map((block, index) => ({
			uuid: block.uuid,
			sort_order: index,
		}))
		await api.reorder(parentUuidFn(), items)
		emit('updated')
	}

	async function addLink(block, data) {
		await api.storeLink(parentUuidFn(), block.uuid, data)
		emit('updated')
	}

	async function saveLink(block, linkUuid, data) {
		await api.updateLink(parentUuidFn(), block.uuid, linkUuid, data)
		emit('updated')
		toast.success('Link gespeichert')
	}

	async function deleteLink(block, linkUuid) {
		const ok = await confirm({
			message: 'Möchtest Du diesen Link wirklich löschen?',
			confirmLabel: 'Löschen',
			variant: 'danger',
		})
		if (!ok) return
		await api.destroyLink(parentUuidFn(), block.uuid, linkUuid)
		emit('updated')
	}

	async function toggleLink(block, linkUuid) {
		await api.toggleLink(parentUuidFn(), block.uuid, linkUuid)
		emit('updated')
	}

	async function reorderLinks(block, items) {
		await api.reorderLinks(parentUuidFn(), block.uuid, items)
		emit('updated')
	}

	return {
		blocks,
		lastCreatedUuid,
		blockTitleForm,
		watchBlocks,
		addBlock,
		blockStoreFn,
		blockUpdateFn,
		onBlockStored,
		updateBlock,
		deleteBlock,
		reorderBlocks,
		addLink,
		saveLink,
		deleteLink,
		toggleLink,
		reorderLinks,
	}
}
