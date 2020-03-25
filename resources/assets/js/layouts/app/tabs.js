const Post = () => import('~/pages/posts/nav.vue')
const Category = () => import('~/pages/categories/nav.vue')
const User = () => import('~/pages/users/nav.vue')
const Tag = () => import('~/pages/tags/nav.vue')

export default [
  {
    name: 'post',
    title: 'Tin tức',
    component: Post,
    route: 'post.list'
  },
  {
    name: 'category',
    title: 'Chuyên mục',
    component: Category,
    route: 'post.category'
  },
  {
    name: 'user',
    title: null,
    component: User,
  },
  {
    name: 'tag',
    title: null,
    component: Tag
  }
]
