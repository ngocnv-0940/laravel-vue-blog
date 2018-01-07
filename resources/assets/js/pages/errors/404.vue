<template>
  <section class="hero has-text-centered is-404">
    <div class="hero-body">
      <div class="container is-uppercase">
        <h1 class="title">
          {{ $t('page_not_found') }}
        </h1>
        <h2 class="subtitle">
          <router-link :to="{ name: 'welcome' }">
            <i class="fa fa-home"></i> {{ $t('go_home') }}
          </router-link>
        </h2>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  name: 'not-found',

  layout: 'default'
}
</script>
