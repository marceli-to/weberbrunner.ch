import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

import LandingIndex from '@/views/landing/IndexPage.vue'
import ProjectsIndex from '@/views/projects/IndexPage.vue'
import ProjectsShow from '@/views/projects/ShowPage.vue'
import ProjectImagesEdit from '@/views/projects/ImagesEditPage.vue'
import ProjectMasterdataEdit from '@/views/projects/MasterdataEditPage.vue'
import ProjectTextEdit from '@/views/projects/TextEditPage.vue'
import ProjectsLayout from '@/views/projects/web/WebLayoutPage.vue'
import ProjectsMetaData from '@/views/projects/web/MetadataPage.vue'
import ProjectsTeaserImage from '@/views/projects/web/TeaserImagePage.vue'
import ProjectsSettings from '@/views/projects/web/SettingsPage.vue'
import PublicationsIndex from '@/views/office/publications/IndexPage.vue'
import PublicationsShow from '@/views/office/publications/ShowPage.vue'
import PublicationsMetadata from '@/views/office/publications/MetadataPage.vue'
import PublicationsTeaserImage from '@/views/office/publications/TeaserImagePage.vue'
import PublicationsSettings from '@/views/office/publications/SettingsPage.vue'
import OfficeIntro from '@/views/office/intro/IndexPage.vue'
import OfficeArbeitsweisen from '@/views/office/arbeitsweisen/IndexPage.vue'
import OfficeTeam from '@/views/office/team/IndexPage.vue'
import OfficeTeamShow from '@/views/office/team/DetailPage.vue'
import OfficeContacts from '@/views/office/contacts/IndexPage.vue'
import OfficeContactsForm from '@/views/office/contacts/FormPage.vue'
import OfficeJobs from '@/views/office/jobs/IndexPage.vue'
import OfficeJobsForm from '@/views/office/jobs/FormPage.vue'
import OfficeNetwork from '@/views/office/network/IndexPage.vue'
import OfficeTalks from '@/views/office/talks/IndexPage.vue'
import OfficeTalksForm from '@/views/office/talks/FormPage.vue'
import OfficeJury from '@/views/office/jury/IndexPage.vue'
import OfficeJuryForm from '@/views/office/jury/FormPage.vue'
import OfficeAwards from '@/views/office/awards/IndexPage.vue'
import OfficeAwardsForm from '@/views/office/awards/FormPage.vue'
import SettingsIndex from '@/views/settings/IndexPage.vue'
import ProfileIndex from '@/views/profile/IndexPage.vue'

const EDITOR = ['admin', 'editor']

const routes = [
  {
    path: '/dashboard',
    redirect: '/dashboard/arbeiten',
  },
  {
    path: '/dashboard/startseite',
    name: 'landing.index',
    component: LandingIndex,
    meta: { title: 'Startseite', navSection: 'main', navLabel: 'Startseite', navOrder: 5 },
  },
  {
    path: '/dashboard/arbeiten',
    name: 'projects.index',
    component: ProjectsIndex,
    meta: { title: 'Arbeiten', navSection: 'main', navLabel: 'Arbeiten', navOrder: 10 },
  },
  {
    path: '/dashboard/arbeiten/:id',
    name: 'projects.show',
    component: ProjectsShow,
    meta: { title: 'Arbeiten', navSection: 'main', navParent: 'projects.index' },
  },
  {
    path: '/dashboard/arbeiten/:id/web',
    name: 'projects.layout',
    component: ProjectsLayout,
    meta: { title: 'Arbeiten', navSection: 'main', navParent: 'projects.index' },
  },
{
    path: '/dashboard/arbeiten/:id/web/metadaten',
    name: 'projects.metadata',
    component: ProjectsMetaData,
    meta: { title: 'Arbeiten', navSection: 'main', navParent: 'projects.index' },
  },
  {
    path: '/dashboard/arbeiten/:id/web/teaserbild',
    name: 'projects.teaser_image',
    component: ProjectsTeaserImage,
    meta: { title: 'Arbeiten', navSection: 'main', navParent: 'projects.index' },
  },
  {
    path: '/dashboard/arbeiten/:id/web/einstellungen',
    name: 'projects.settings',
    component: ProjectsSettings,
    meta: { title: 'Arbeiten', navSection: 'main', navParent: 'projects.index' },
  },
  {
    path: '/dashboard/arbeiten/:id/stammdaten',
    name: 'projects.masterdata.edit',
    component: ProjectMasterdataEdit,
    meta: { title: 'Arbeiten', navSection: 'main', navParent: 'projects.index', roles: EDITOR },
  },
  {
    path: '/dashboard/arbeiten/:id/bilder',
    name: 'projects.images.edit',
    component: ProjectImagesEdit,
    meta: { title: 'Arbeiten', navSection: 'main', navParent: 'projects.index', roles: EDITOR },
  },
  {
    path: '/dashboard/arbeiten/:id/texte',
    name: 'projects.text.edit',
    component: ProjectTextEdit,
    meta: { title: 'Arbeiten', navSection: 'main', navParent: 'projects.index', roles: EDITOR },
  },
  {
    path: '/dashboard/buero/intro',
    name: 'office.intro',
    component: OfficeIntro,
    meta: { title: 'Intro', navSection: 'office', navLabel: 'Intro', navOrder: 1, navMain: { label: 'Büro', order: 20 } },
  },
  {
    path: '/dashboard/buero/arbeitsweisen',
    name: 'office.arbeitsweisen',
    component: OfficeArbeitsweisen,
    meta: { title: 'Arbeitsweisen', navSection: 'office', navLabel: 'Arbeitsweisen', navOrder: 5 },
  },
  {
    path: '/dashboard/buero/team',
    name: 'office.team',
    component: OfficeTeam,
    meta: { title: 'Team', navSection: 'office', navLabel: 'Team', navOrder: 10 },
  },
  {
    path: '/dashboard/buero/team/:id',
    name: 'team.show',
    component: OfficeTeamShow,
    meta: { title: 'Team', navSection: 'office', navParent: 'office.team' },
  },
  {
    path: '/dashboard/buero/publikationen',
    name: 'office.publications',
    component: PublicationsIndex,
    meta: { title: 'Publikationen', navSection: 'office', navLabel: 'Publikationen', navOrder: 15 },
  },
  {
    path: '/dashboard/buero/publikationen/:id',
    name: 'publications.show',
    component: PublicationsShow,
    meta: { title: 'Publikationen', navSection: 'office', navParent: 'office.publications' },
  },
  {
    path: '/dashboard/buero/publikationen/:id/metadaten',
    name: 'publications.metadata',
    component: PublicationsMetadata,
    meta: { title: 'Publikationen', navSection: 'office', navParent: 'office.publications' },
  },
  {
    path: '/dashboard/buero/publikationen/:id/teaserbild',
    name: 'publications.teaser_image',
    component: PublicationsTeaserImage,
    meta: { title: 'Publikationen', navSection: 'office', navParent: 'office.publications' },
  },
  {
    path: '/dashboard/buero/publikationen/:id/einstellungen',
    name: 'publications.settings',
    component: PublicationsSettings,
    meta: { title: 'Publikationen', navSection: 'office', navParent: 'office.publications' },
  },
  {
    path: '/dashboard/buero/kontakt',
    name: 'office.contacts',
    component: OfficeContacts,
    meta: { title: 'Kontakt', navSection: 'office', navLabel: 'Kontakt', navOrder: 70 },
  },
  {
    path: '/dashboard/buero/kontakt/erstellen',
    name: 'contacts.create',
    component: OfficeContactsForm,
    meta: { title: 'Kontakt', navSection: 'office', navParent: 'office.contacts', roles: EDITOR },
  },
  {
    path: '/dashboard/buero/kontakt/:id/bearbeiten',
    name: 'contacts.edit',
    component: OfficeContactsForm,
    meta: { title: 'Kontakt', navSection: 'office', navParent: 'office.contacts', roles: EDITOR },
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
    meta: { title: 'Jobs', navSection: 'office', navParent: 'office.jobs', roles: EDITOR },
  },
  {
    path: '/dashboard/buero/jobs/:id/bearbeiten',
    name: 'jobs.edit',
    component: OfficeJobsForm,
    meta: { title: 'Jobs', navSection: 'office', navParent: 'office.jobs', roles: EDITOR },
  },
  {
    path: '/dashboard/buero/netzwerk',
    name: 'office.network',
    component: OfficeNetwork,
    meta: { title: 'Netzwerk', navSection: 'office', navLabel: 'Netzwerk', navOrder: 30 },
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
    meta: { title: 'Vorträge', navSection: 'office', navParent: 'office.talks', roles: EDITOR },
  },
  {
    path: '/dashboard/buero/vortraege/:id/bearbeiten',
    name: 'talks.edit',
    component: OfficeTalksForm,
    meta: { title: 'Vorträge', navSection: 'office', navParent: 'office.talks', roles: EDITOR },
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
    meta: { title: 'Jury', navSection: 'office', navParent: 'office.jury', roles: EDITOR },
  },
  {
    path: '/dashboard/buero/jury/:id/bearbeiten',
    name: 'jury.edit',
    component: OfficeJuryForm,
    meta: { title: 'Jury', navSection: 'office', navParent: 'office.jury', roles: EDITOR },
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
    meta: { title: 'Auszeichnungen', navSection: 'office', navParent: 'office.awards', roles: EDITOR },
  },
  {
    path: '/dashboard/buero/auszeichnungen/:id/bearbeiten',
    name: 'awards.edit',
    component: OfficeAwardsForm,
    meta: { title: 'Auszeichnungen', navSection: 'office', navParent: 'office.awards', roles: EDITOR },
  },
  {
    path: '/dashboard/voreinstellungen',
    name: 'settings.index',
    component: SettingsIndex,
    meta: { title: 'Voreinstellungen', navSection: 'main', navLabel: 'Voreinstellungen', navOrder: 30, roles: EDITOR },
  },
  {
    path: '/dashboard/profil',
    name: 'profile.index',
    component: ProfileIndex,
    meta: { title: 'Profil' },
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

router.beforeEach(async (to) => {
  if (!to.meta.roles) return true
  const auth = useAuthStore()
  await auth.ensureUser()
  const role = auth.user?.role
  if (role && to.meta.roles.includes(role)) return true
  return { name: 'projects.index' }
})

router.afterEach((to) => {
  document.title = to.meta.title ? `${to.meta.title} – DataHub` : 'DataHub'
})

export default router
