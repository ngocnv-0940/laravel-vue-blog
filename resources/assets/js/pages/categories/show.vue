<template>
  <div class="container">
    <div class="columns">
      <div class="is-three-quarters column">
        <template v-if="loaded">
          <template v-if="posts.length">
            <post :post="post" v-for="post in posts" :key="post.slug"></post>
            <b-pagination
              :total="total"
              :current.sync="page"
              order="is-centered"
              @change="changePage"
              per-page="15">
            </b-pagination>
          </template>
          <p class="has-text-centered subtitle" v-else>Chưa có nội dung, hãy <router-link :to="{ name: 'post.create' }">đăng bài đầu tiên</router-link>!</p>
        </template>
      </div>

      <div class="column">
        <section class="right-sidebar">
          <article class="media">
            <figure class="media-left">
              <p class="image is-64x64">
                <img src="http://bulma.io/images/placeholders/128x128.png">
              </p>
            </figure>
            <div class="media-content">
              <div class="content">
                <p>
                  <strong>{{ category.name }}</strong><br>
                  <small>hello</small>
                </p>
              </div>
            </div>
          </article>
          <article class="media">
            <table class="table is-fullwidth">
              <tr>
                <th>Tồng bài viết</th>
                <td class="has-text-right">{{ category.posts_count }}</td>
              </tr>
            </table>
          </article>
        </section>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import Post from '../posts/post'
export default {
  metaInfo () {
    return { title: this.category.name || 'Đang tải' }
  },
  data() {
    return {
      posts: [],
      category: {},
      page: +this.$route.params.page || 1,
      total: 0,
      loaded: false
    }
  },
  async created() {
    await this.getPosts()
    this.loaded = true
    this.$store.commit('setTitle', { title: this.category.name })
  },
  methods: {
    async getPosts() {
      const loading = this.$loading.open()
      try {
        let { data } = await axios.get(`category/${this.$route.params.slug}?page=${this.page}`)
        if (data.posts.current_page <= data.posts.last_page) {
          this.posts = data.posts.data
          this.category = data.category
          this.total = data.posts.total
        } else {
          this.$toast.open({
            message: this.$t('error_alert_text'),
            type: 'is-danger'
          })
        }
      } catch(e) {}
      loading.close()
    },
    changePage(page) {
      if (page == 1)
        this.$router.push({ name: this.$route.name, params: { slug: this.$route.params.slug }})
      else
        this.$router.push({ name: this.$route.name, params: { slug: this.$route.params.slug, page: page }})
    },
  },
  watch: {
    async '$route' (to, from) {
      this.page = +to.params.page
      if (to.params.page == undefined)
        this.page = 1
      await this.getPosts()
      this.$store.commit('setTitle', { title: this.category.name })
    }
  },
  components: {
    Post
  }
}
</script>
