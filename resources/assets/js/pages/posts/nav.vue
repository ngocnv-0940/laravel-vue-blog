<template>
  <div class="navbar-tabs">
    <router-link :to="{ name: 'post.list' }" class="navbar-item is-tab">Mới nhất</router-link>
    <router-link :to="{ name: 'post.featured' }" class="navbar-item is-tab">Nổi bật</router-link>
  </div>
</template>
