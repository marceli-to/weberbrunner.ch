<script setup>
import { useRouter } from 'vue-router'
import { useBlogStore } from '@/stores/blog'
import { usePageLoader } from '@/composables/usePageLoader'
import { useToast } from '@/composables/useToast'
import { useConfirm } from '@/composables/useConfirm'
import Burger from '@/components/icons/Burger.vue'
import draggable from 'vuedraggable'

const router = useRouter()
const store = useBlogStore()
const { load } = usePageLoader()
const toast = useToast()
const { confirm } = useConfirm()

load(() => store.fetchAll())

async function handleDelete(post) {
	const ok = await confirm({
		message: `Möchtest Du den Eintrag «${post.title}» wirklich löschen?`,
		confirmLabel: 'Löschen',
		variant: 'danger',
	})
	if (!ok) return
	await store.destroy(post.id)
	toast.success('Post deleted')
}
</script>

<template>
	<div class="grid grid-cols-10 gap-20 w-full">
    <div class="col-span-9">
      
      <div class="flex items-center justify-between mb-20">
        <h2 class="text-lg font-semibold text-black">Blog Posts</h2>
        <button
          class="bg-black text-white text-sm font-semibold px-16 py-8"
          @click="router.push({ name: 'blog.create' })"
        >
          New Post
        </button>
      </div>

      <div v-if="store.posts.length === 0" class="text-sm text-gray">
        No posts yet.
      </div>

      <table v-else class="w-full text-sm">
        <thead>
          <tr class="border-b-thin border-silver text-left">
            <th></th>
            <th class="py-8 font-semibold text-gray">Title</th>
            <th class="py-8 font-semibold text-gray w-80">Status</th>
            <th class="py-8 font-semibold text-gray w-128">Created</th>
            <th class="py-8 font-semibold text-gray w-128 text-right">Actions</th>
          </tr>
        </thead>
        <draggable
          v-model="store.posts"
          tag="tbody"
          item-key="id"
          handle=".drag-handle"
          ghost-class="bg-snow"
          drag-class="bg-white"
          @end="store.reorder"
        >
          <template #item="{ element: post }">
            <tr class="border-b-thin border-silver">
              <td class="py-12 w-24">
                <Burger class="w-14 cursor-grab drag-handle" />
              </td>
              <td class="py-12 text-black">{{ post.title }}</td>
              <td class="py-12">
                <span
                  class="text-sm"
                  :class="post.publish ? 'text-lime' : 'text-gray'"
                >
                  {{ post.publish ? 'Published' : 'Draft' }}
                </span>
              </td>
              <td class="py-12 text-gray">
                {{ new Date(post.created_at).toLocaleDateString('de-CH') }}
              </td>
              <td class="py-12 text-right">
                <button
                  class="text-black font-semibold mr-12"
                  @click="router.push({ name: 'blog.edit', params: { id: post.id } })"
                >
                  Edit
                </button>
                <button
                  class="text-red font-semibold"
                  @click="handleDelete(post)"
                >
                  Delete
                </button>
              </td>
            </tr>
          </template>
        </draggable>
      </table>
    </div>
	</div>
</template>
