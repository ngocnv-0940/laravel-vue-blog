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
          <h2 class="subtitle">{{ tag.name }}</h2>
          {{ tag }}
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
    return { title: this.tag.name || 'Đang tải' }
  },
  data() {
    return {
      posts: [],
      tag: {},
      page: +this.$route.params.page || 1,
      total: 0,
      loaded: false
    }
  },
  async created() {
    await this.getPosts()
    this.loaded = true
  },
  methods: {
    async getPosts() {
      const loading = this.$loading.open()
      try {
        let { data } = await axios.get(`tag/${this.$route.params.slug}?page=${this.page}`)
        if (data.posts.current_page <= data.posts.last_page) {
          this.posts = data.posts.data
          this.tag = data.tag
          this.total = data.posts.total
          this.$store.commit('setTitle', { title: this.tag.name })
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
    '$route' (to, from) {
      this.page = +to.params.page
      if (to.params.page == undefined)
        this.page = 1
      this.getPosts()
    }
  },
  components: {
    Post
  }
}
</script>
