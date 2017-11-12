<template>
  <div class="container">
    <div class="columns">
      <div class="is-three-quarters column">
        <post :post="post" v-for="post in posts" :key="post.slug"></post>
        <b-pagination
          :total="total"
          :current.sync="page"
          order="is-centered"
          @change="changePage"
          per-page="15">
        </b-pagination>
      </div>
      <div class="column">
        <section class="right-sidebar">
          <h2 class="subtitle">{{ category.name }}</h2>
          {{ category }}
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
      total: 0
    }
  },
  async created() {
    await this.getPosts()
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
