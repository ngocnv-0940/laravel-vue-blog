<template>
  <article class="media">
    <figure class="media-left is-hidden-mobile">
      <p class="image is-128x128">
        <img :src="post.thumb">
      </p>
    </figure>
    <div class="media-content">
      <div class="content">
          <router-link :to="{ name: 'post.show', params: { slug: post.slug }}">
            <h2 class=" is-size-5 has-text-dark">
              <button class="button is-danger is-outlined is-small" v-if="post.is_public == false">Bản nháp</button>
              {{ post.title }}
            </h2>
          </router-link>
          <p class="has-text-justified">{{ post.excerpt }}</p>
          <b-taglist style="margin-top: 0.5em">
            <b-tag rounded v-for="tag in post.tags" :key="tag.id">
              <router-link :to="{ name: 'tag.show', params: { slug: tag.slug }}">
                {{ tag.name }}
              </router-link>
            </b-tag>
          </b-taglist>
      </div>
      <nav class="level is-mobile">
        <div class="level-left">
          <router-link
            class="level-item"
            v-if="post.category"
            :to="{ name: 'post.category', params: { slug: post.category.slug }}">
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
        </div>
        <div class="level-right">
          <a class="level-item">
            <b-tooltip label="Reply">
              <span class="icon is-small"><i class="fa fa-reply"></i></span>
            </b-tooltip>
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
    </div>
    <div class="media-right is-hidden-touch">
      <small class="is-size-7">
        <timeago
          :since="post.created_at"
          :autoUpdate="5"
          :max-time="86400 * 365">
        </timeago>
      </small>
    </div>
  </article>
</template>
<script>
export default {
  props: {
    post: {
      type: Object,
      required: true
    }
  }
}
</script>
