<template>
  <div class="navbar-brand is-inline-mobile">
    <a class="navbar-item is-tab"
      :class="{ 'is-active': checkActive(category) }"
      v-for="category in categories"
      :key="category.slug">
      <b-dropdown hoverable mobile-modal>
        <template slot="trigger">
          {{ category.name }}
          <b-icon icon="arrow_drop_down"></b-icon>
        </template>

        <b-dropdown-item has-link v-for="child in category.childs" :key="child.slug" :value="child">
          <router-link :to="{ name: 'post.category', params: { slug: child.slug }}">
            {{ child.name }}
            <b-tag rounded>{{ child.posts_count }}</b-tag>
          </router-link>
        </b-dropdown-item>
      </b-dropdown>
    </a>
  </div>
</template>
<script>
  import axios from 'axios'
  import { mapGetters } from 'vuex'
  export default {
    computed: mapGetters(['categories']),
    methods: {
      checkActive(category) {
        return category.childs.length
          ? category.childs.some(child => child.slug == this.$route.params.slug)
          : false
      }
    }
  }
</script>
