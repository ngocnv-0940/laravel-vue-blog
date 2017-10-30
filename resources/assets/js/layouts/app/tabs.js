import Post from '../../pages/posts/PostNav.vue'
const Category = () => import('../../pages/categories/CategoryNav.vue')

export default [
  {
    title: 'Tin tức',
    component: Post,
    route: 'post.list'
  },
  {
    title: 'Chuyên mục',
    component: Category,
    route: 'post.category',
    params: { slug: 'prof-burley-ullrich-phd' }
  }
]
