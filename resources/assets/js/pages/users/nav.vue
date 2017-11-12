<template>
  <div class="navbar-brand">
    <router-link
      :to="{ name: 'user.show', params: { username: $route.params.username }}"
      class="navbar-item is-tab">
      Bài đăng
  </router-link>
    <router-link
      v-if="authCheck && authUser.username == $route.params.username"
      :to="{ name: 'user.drafts', params: { username: $route.params.username }}"
      class="navbar-item is-tab">
      Bài nháp
  </router-link>
  </div>
</template>

<script>
  import { mapGetters } from 'vuex'
  export default {
    computed: mapGetters(['authUser', 'authCheck'])
  }
</script>
