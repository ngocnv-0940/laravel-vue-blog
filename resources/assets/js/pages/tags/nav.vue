<template>
  <div class="navbar-tabs">
    <router-link :to="{ name: 'tag.show', params: { slug: $route.params.slug }}" class="navbar-item is-tab">Bài viết</router-link>
    <router-link to="#" class="navbar-item is-tab">Video</router-link>
  </div>
</template>
