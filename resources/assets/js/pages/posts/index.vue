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
          <h2 class="subtitle">Popular tags</h2>
          <b-field grouped group-multiline>
            <div class="control" v-for="tag in tags" :key="tag.slug">
              <router-link :to="{ name: 'tag.show', params: { slug: tag.slug }}">
                <b-taglist attached>
                  <b-tag type="is-primary">{{ tag.name }}</b-tag>
                  <b-tag type="is-secondary">{{ tag.posts_count }}</b-tag>
                </b-taglist>
              </router-link>
            </div>
          </b-field>
        </section>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import Post from './post'
export default {
  metaInfo () {
    return { title: 'Tin tức' }
  },
  props: {
    params: {
      type: Object,
      required: false
    }
  },
  data() {
    return {
      posts: [],
      tags: [],
      page: +this.$route.params.page || 1,
      loading: false,
      total: 0
    }
  },
  created() {
    this.$store.commit('setTitle', { title: 'Tin tức' })
    this.getPosts()
    this.getTags()
  },
  methods: {
    async getPosts() {
      const loading = this.$loading.open()
      let { data } = await axios.get(route('post.index'), {
        params: {
          ...this.params,
          page: this.page
        }
      })
      if (data.meta.current_page <= data.meta.last_page) {
        this.posts = data.data
        this.total = data.meta.total
      } else {
        this.$toast.open({
          message: this.$t('error_alert_text'),
          type: 'is-danger'
        })
      }
      loading.close()
    },
    async getTags() {
      let { data: { data: tags }} = await axios.get(route('tag.index'))
      this.tags = tags
    },
    changePage(page) {
      if (page == 1)
        this.$router.push({ name: this.$route.name})
      else
        this.$router.push({ name: this.$route.name, params: { page: page }})
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
