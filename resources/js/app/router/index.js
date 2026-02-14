import { createRouter, createWebHistory } from 'vue-router'

// demo
import Components from '@/views/Components.vue'
import BlogIndex from '@/views/blog/Index.vue'
import BlogForm from '@/views/blog/Form.vue'
// -- demo

import ProjectsIndex from '@/views/projects/Index.vue'
import OfficeTeam from '@/views/office/team/Index.vue'
import OfficeJobs from '@/views/office/jobs/Index.vue'
import OfficeNetwork from '@/views/office/network/Index.vue'
import OfficeTalks from '@/views/office/talks/Index.vue'
import OfficeJury from '@/views/office/jury/Index.vue'
import OfficeAwards from '@/views/office/awards/Index.vue'
import OfficeAwardsForm from '@/views/office/awards/Form.vue'
import SettingsIndex from '@/views/settings/Index.vue'
import ProfileIndex from '@/views/profile/Index.vue'

const routes = [
  {
    path: '/dashboard',
    redirect: '/dashboard/arbeiten',
  },
  {
    path: '/dashboard/components',
    name: 'components',
    component: Components,
    meta: { title: 'Components' },
  },
  {
    path: '/dashboard/arbeiten',
    name: 'projects.index',
    component: ProjectsIndex,
    meta: { title: 'Arbeiten' },
  },
  {
    path: '/dashboard/buero/team',
    name: 'office.team',
    component: OfficeTeam,
    meta: { title: 'Team' },
  },
  {
    path: '/dashboard/buero/jobs',
    name: 'office.jobs',
    component: OfficeJobs,
    meta: { title: 'Jobs' },
  },
  {
    path: '/dashboard/buero/netzwerk',
    name: 'office.network',
    component: OfficeNetwork,
    meta: { title: 'Netzwerk' },
  },
  {
    path: '/dashboard/buero/vortraege',
    name: 'office.talks',
    component: OfficeTalks,
    meta: { title: 'Vorträge' },
  },
  {
    path: '/dashboard/buero/jury',
    name: 'office.jury',
    component: OfficeJury,
    meta: { title: 'Jury' },
  },
  {
    path: '/dashboard/buero/auszeichnungen',
    name: 'office.awards',
    component: OfficeAwards,
    meta: { title: 'Auszeichnungen' },
  },
  {
    path: '/dashboard/buero/auszeichnungen/erstellen',
    name: 'awards.create',
    component: OfficeAwardsForm,
    meta: { title: 'Auszeichnungen' },
  },
  {
    path: '/dashboard/buero/auszeichnungen/:id/bearbeiten',
    name: 'awards.edit',
    component: OfficeAwardsForm,
    meta: { title: 'Auszeichnungen' },
  },
  {
    path: '/dashboard/voreinstellungen',
    name: 'settings.index',
    component: SettingsIndex,
    meta: { title: 'Voreinstellungen' },
  },
  {
    path: '/dashboard/profil',
    name: 'profile.index',
    component: ProfileIndex,
    meta: { title: 'Profil' },
  },
  {
    path: '/dashboard/blog',
    name: 'blog.index',
    component: BlogIndex,
    meta: { title: 'Blog' },
  },
  {
    path: '/dashboard/blog/create',
    name: 'blog.create',
    component: BlogForm,
    meta: { title: 'Blog' },
  },
  {
    path: '/dashboard/blog/:id/edit',
    name: 'blog.edit',
    component: BlogForm,
    meta: { title: 'Blog' },
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

router.afterEach((to) => {
  document.title = to.meta.title
    ? `${to.meta.title} – DataHub`
    : 'DataHub'
})

export default router
