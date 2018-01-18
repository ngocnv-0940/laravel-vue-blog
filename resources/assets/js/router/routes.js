const PostIndex = () => import('~/pages/posts/index').then(m => m.default || m)
const PostShow = () => import('~/pages/posts/show').then(m => m.default || m)
const PostCreate = () => import('~/pages/posts/create').then(m => m.default || m)
const PostEdit = () => import('~/pages/posts/edit').then(m => m.default || m)

const CategoryShow = () => import('~/pages/categories/show').then(m => m.default || m)
const TagShow = () => import('~/pages/tags/show').then(m => m.default || m)
const UserShow = () => import('~/pages/users/show').then(m => m.default || m)

const Welcome = () => import('~/pages/welcome').then(m => m.default || m)
import Search from '~/pages/posts/search'

export default [
  { path: '/search', component: Search, props: (route) => ({ query: route.query.q })  },
  { path: '/', name: 'welcome', component: Welcome },
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
    meta: { tab: 'post' }
  },
  {
    path: '/post/:page(\\d+)?', name: 'post.list', component: PostIndex,
    meta: { tab: 'post' }
  },
  { path: '/post/:slug', name: 'post.show', component: PostShow,
    meta: { tab: 'post' }
  },

  // Authenticated routes.
  ...middleware('auth', [
    { path: '/settings', component: require('~/pages/settings/index'), children: [
      { path: '', redirect: { name: 'settings.profile' }},
      { path: 'profile', name: 'settings.profile', component: require('~/pages/settings/profile') },
      { path: 'password', name: 'settings.password', component: require('~/pages/settings/password') }
    ] },
    { path: '/create/post', name: 'post.create', component: PostCreate},
    { path: '/post/:slug/edit', name: 'post.edit', component: PostEdit, meta: { tab: 'post' }},
  ]),

  ...middleware('admin', [
    { path: '/admin', name: 'admin', component: PostCreate },
  ]),

  // Guest routes.
  ...middleware('guest', [
    { path: '/password/reset', name: 'password.request', component: require('~/pages/auth/password/email') },
    { path: '/password/reset/:token', name: 'password.reset', component: require('~/pages/auth/password/reset') }
  ]),

  { path: '*', component: require('~/pages/errors/404') }
]

/**
 * @param  {String|Function} middleware
 * @param  {Array} routes
 * @return {Array}
 */
function middleware (middleware, routes) {
  routes.forEach(route =>
    (route.middleware || (route.middleware = [])).unshift(middleware)
  )

  return routes
}
