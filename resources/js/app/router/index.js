import { createRouter, createWebHistory } from 'vue-router'

// demo
import Components from '@/views/Components.vue'
import BlogIndex from '@/views/blog/Index.vue'
import BlogForm from '@/views/blog/Form.vue'
// -- demo

import ProjectsIndex from '@/views/projects/Index.vue'
import OfficeTeam from '@/views/office/team/Index.vue'
import OfficeJobs from '@/views/office/jobs/Index.vue'
import OfficeJobsForm from '@/views/office/jobs/Form.vue'
import OfficeNetwork from '@/views/office/network/Index.vue'
import OfficeNetworkForm from '@/views/office/network/Form.vue'
import OfficeTalks from '@/views/office/talks/Index.vue'
import OfficeTalksForm from '@/views/office/talks/Form.vue'
import OfficeJury from '@/views/office/jury/Index.vue'
import OfficeJuryForm from '@/views/office/jury/Form.vue'
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
    meta: { title: 'Arbeiten', navSection: 'main', navLabel: 'Arbeiten', navOrder: 10 },
  },
  {
    path: '/dashboard/buero/team',
    name: 'office.team',
    component: OfficeTeam,
    meta: { title: 'Team', navSection: 'office', navLabel: 'Team', navOrder: 10, navMain: { label: 'Büro', order: 20 } },
  },
  {
    path: '/dashboard/buero/jobs',
    name: 'office.jobs',
    component: OfficeJobs,
    meta: { title: 'Jobs', navSection: 'office', navLabel: 'Jobs', navOrder: 20 },
  },
  {
    path: '/dashboard/buero/jobs/erstellen',
    name: 'jobs.create',
    component: OfficeJobsForm,
    meta: { title: 'Jobs', navSection: 'office', navParent: 'office.jobs' },
  },
  {
    path: '/dashboard/buero/jobs/:id/bearbeiten',
    name: 'jobs.edit',
    component: OfficeJobsForm,
    meta: { title: 'Jobs', navSection: 'office', navParent: 'office.jobs' },
  },
  {
    path: '/dashboard/buero/netzwerk',
    name: 'office.network',
    component: OfficeNetwork,
    meta: { title: 'Netzwerk', navSection: 'office', navLabel: 'Netzwerk', navOrder: 30 },
  },
  {
    path: '/dashboard/buero/netzwerk/erstellen',
    name: 'network.create',
    component: OfficeNetworkForm,
    meta: { title: 'Netzwerk', navSection: 'office', navParent: 'office.network' },
  },
  {
    path: '/dashboard/buero/netzwerk/:id/bearbeiten',
    name: 'network.edit',
    component: OfficeNetworkForm,
    meta: { title: 'Netzwerk', navSection: 'office', navParent: 'office.network' },
  },
  {
    path: '/dashboard/buero/vortraege',
    name: 'office.talks',
    component: OfficeTalks,
    meta: { title: 'Vorträge', navSection: 'office', navLabel: 'Vorträge', navOrder: 40 },
  },
  {
    path: '/dashboard/buero/vortraege/erstellen',
    name: 'talks.create',
    component: OfficeTalksForm,
    meta: { title: 'Vorträge', navSection: 'office', navParent: 'office.talks' },
  },
  {
    path: '/dashboard/buero/vortraege/:id/bearbeiten',
    name: 'talks.edit',
    component: OfficeTalksForm,
    meta: { title: 'Vorträge', navSection: 'office', navParent: 'office.talks' },
  },
  {
    path: '/dashboard/buero/jury',
    name: 'office.jury',
    component: OfficeJury,
    meta: { title: 'Jury', navSection: 'office', navLabel: 'Jury', navOrder: 50 },
  },
  {
    path: '/dashboard/buero/jury/erstellen',
    name: 'jury.create',
    component: OfficeJuryForm,
    meta: { title: 'Jury', navSection: 'office', navParent: 'office.jury' },
  },
  {
    path: '/dashboard/buero/jury/:id/bearbeiten',
    name: 'jury.edit',
    component: OfficeJuryForm,
    meta: { title: 'Jury', navSection: 'office', navParent: 'office.jury' },
  },
  {
    path: '/dashboard/buero/auszeichnungen',
    name: 'office.awards',
    component: OfficeAwards,
    meta: { title: 'Auszeichnungen', navSection: 'office', navLabel: 'Auszeichnungen', navOrder: 60 },
  },
  {
    path: '/dashboard/buero/auszeichnungen/erstellen',
    name: 'awards.create',
    component: OfficeAwardsForm,
    meta: { title: 'Auszeichnungen', navSection: 'office', navParent: 'office.awards' },
  },
  {
    path: '/dashboard/buero/auszeichnungen/:id/bearbeiten',
    name: 'awards.edit',
    component: OfficeAwardsForm,
    meta: { title: 'Auszeichnungen', navSection: 'office', navParent: 'office.awards' },
  },
  {
    path: '/dashboard/voreinstellungen',
    name: 'settings.index',
    component: SettingsIndex,
    meta: { title: 'Voreinstellungen', navSection: 'main', navLabel: 'Voreinstellungen', navOrder: 30 },
  },
  {
    path: '/dashboard/profil',
    name: 'profile.index',
    component: ProfileIndex,
    meta: { title: 'Profil', navSection: 'main', navLabel: 'Profil', navOrder: 40 },
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
  document.title = to.meta.title ? `${to.meta.title} – DataHub` : 'DataHub'
})

export default router
