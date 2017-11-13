const PostIndex = () => import('~/pages/posts/index.vue')
const PostShow = () => import('~/pages/posts/show.vue')
const PostCreate = () => import('~/pages/posts/create.vue')
const PostEdit = () => import('~/pages/posts/edit.vue')

const CategoryShow = () => import('~/pages/categories/show.vue')
const TagShow = () => import('~/pages/tags/show.vue')
const UserShow = () => import('~/pages/users/show.vue')
import Search from '~/pages/posts/search.vue'

export default ({ authGuard, guestGuard }) => [
  { path: '/search', component: Search, props: (route) => ({ query: route.query.q })  },
  { path: '/', name: 'welcome', component: require('~/pages/welcome.vue') },
  {
    path: '/category/:slug/:page(\\d+)?', name: 'post.category', component: CategoryShow,
    meta: { tab: 'category' }
  },
  {
    path: '/user/:username/drafts/:page(\\d+)?', name: 'user.drafts', component: UserShow,
    props: { params: { scope: 'drafts' }},
    meta: { tab: 'user' }
  },
  {
    path: '/user/:username/:page(\\d+)?', name: 'user.show', component: UserShow,
    meta: { tab: 'user' }
  },
  {
    path: '/tag/:slug/:page(\\d+)?', name: 'tag.show', component: TagShow,
    meta: { tab: 'tag' }
  },
  {
    path: '/post/featured/:page(\\d+)?', name: 'post.featured', component: PostIndex,
    props: { params: { scope: 'featured' }},
    meta: { scrollToTop: true, tab: 'post' }
  },
  {
    path: '/post/:page(\\d+)?', name: 'post.list', component: PostIndex,
    meta: { scrollToTop: true, tab: 'post' }
  },
  { path: '/post/:slug', name: 'post.show', component: PostShow,
    meta: { scrollToTop: true, tab: 'post' }
  },

  // Authenticated routes.
  ...authGuard([
    { path: '/home', name: 'home', component: require('~/pages/home.vue') },
    { path: '/settings', component: require('~/pages/settings/index.vue'), children: [
      { path: '', redirect: { name: 'settings.profile' }},
      { path: 'profile', name: 'settings.profile', component: require('~/pages/settings/profile.vue') },
      { path: 'password', name: 'settings.password', component: require('~/pages/settings/password.vue') }
    ] },
    { path: '/create/post', name: 'post.create', component: PostCreate},
    { path: '/post/:slug/edit', name: 'post.edit', component: PostEdit, meta: { tab: 'post' }},
  ]),

  // Guest routes.
  ...guestGuard([
    { path: '/password/reset', name: 'password.request', component: require('~/pages/auth/password/email.vue') },
    { path: '/password/reset/:token', name: 'password.reset', component: require('~/pages/auth/password/reset.vue') }
  ]),

  { path: '*', component: require('~/pages/errors/404.vue') }
]
