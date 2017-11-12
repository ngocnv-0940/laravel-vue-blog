<template>
  <div class="container">
    <div class="columns">
      <div class="is-three-quarters column">
        <h1 class="title is-spaced">
          <button class="button is-danger is-outlined" v-if="post.is_public === false">Bản nháp</button>
          {{ post.title }}
        </h1>
        <p class="content">{{ post.excerpt }}</p>
        <nav class="level">
          <div class="level-left" v-if="post.slug">
            <router-link :to="{ name: 'post.category', params: { slug: post.category.slug }}" class="level-item">
              <span class="icon icon-small"><i class="fa fa-folder"></i></span>
              <small>{{ post.category.name }}</small>
            </router-link>
            <router-link
              class="level-item"
              :to="{ name: 'user.show', params: { username: post.author.username }}">
              <span class="icon icon-small"><i class="fa fa-user"></i></span>
              <small>{{ post.author.name }}</small>
            </router-link>
            <a class="level-item">
              <span class="icon icon-small"><i class="fa fa-clock-o"></i></span>
              <small>
                <timeago
                  :since="post.created_at"
                  :max-time="86400 * 365"
                  :autoUpdate="5">
                </timeago>
              </small>
            </a>
          </div>
          <div class="level-right">
            <a class="level-item">
              <span class="icon icon-small"><i class="fa fa-eye"></i></span>
              <small>10</small>
            </a>
            <a class="level-item">
              <span class="icon icon-small"><i class="fa fa-comments"></i></span>
              <small>{{ post.comments_count }}</small>
            </a>
            <a class="level-item">
              <span class="icon is-small"><i class="fa fa-heart"></i></span>
              <small>{{ post.likes_count }}</small>
            </a>
            <a class="level-item">
              <b-tooltip label="Retweet">
                <span class="icon is-small"><i class="fa fa-retweet"></i></span>
              </b-tooltip>
            </a>
            <a class="level-item">
              <b-tooltip label="Copy URL">
                <span class="icon is-small"><i class="fa fa-link"></i></span>
              </b-tooltip>
            </a>
          </div>
        </nav>
        <hr>
        <div class="content has-text-justified">
          <markdown :text="post.content"></markdown>
        </div>
        <nav class="level">
          <div class="level-left">
            <b-tooltip :label="hasLiked ? 'Bỏ thích' : 'Yêu thích'" type="is-dark">
              <a class="button level-item is-outlined"
                :class="{ 'is-danger': hasLiked, 'is-primary': !hasLiked }"
                @click="authCheck ? likeOrUnlike('post') : null">
                <b-icon pack="mdi" icon="favorite"></b-icon>
                <small>{{ post.likes_count }} Thích</small>
              </a>
            </b-tooltip>
          </div>

          <div class="level-right">
            <b-taglist style="margin-top: 0.5em">
              <b-tag rounded v-for="tag in post.tags" :key="tag.slug" class="level-item">
                <router-link :to="{ name: 'tag.show', params: { slug: tag.slug }}">
                  {{ tag.name }}
                </router-link>
              </b-tag>
            </b-taglist>
          </div>
        </nav>
        <hr>
        <comment></comment>
      </div>
      <div class="column is-12-touch">
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
                  <strong>{{ post.author.name }}</strong>
                  <small>@johnsmith</small>
                </p>
              </div>
            </div>
          </article>
          <button class="button is-small is-outlined is-primary"><i class="fa fa-plus-circle"></i>&nbsp;Follow</button>
        </section>
      </div>
    </div>
  </div>
</template>
<script>
import axios from 'axios'
import Comment from './Comment'
import { mapGetters, mapActions, mapState } from 'vuex'
export default {
  metaInfo() {
    return {
      title: this.post.title || 'Đang tải',
      meta: [
        { name: 'keywords', content: this.post.meta_keywords },
        { name: 'description', content: this.post.meta_description }
      ]
    }
  },
  created() {
    this.fetchArticle('post.show')
    this.fetchComments({ refresh: true })
  },
  computed: {
    ...mapGetters(['authUser', 'authCheck']),
    ...mapGetters('article', ['hasLiked']),
    ...mapState('article', { post: 'article' })
  },
  methods: {
    ...mapActions('article', ['fetchArticle', 'updateArticle', 'likeOrUnlike']),
    ...mapActions('comment', ['fetchComments'])
  },
  watch: {
    '$route' (to, from) {
      this.fetchArticle('post.show')
      this.fetchComments({ refresh: true })
    }
  },
  components: {
    Comment
  }
}
</script>
