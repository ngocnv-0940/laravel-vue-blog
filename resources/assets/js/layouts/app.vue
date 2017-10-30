<template>
  <div>
    <site-header/>
    <section class="section">
      <transition name="slide-fade">
        <child/>
      </transition>
    </section>
    <site-footer/>
  </div>
</template>

<script>
import SiteHeader from './app/Header.vue'
import SiteFooter from './app/Footer.vue'

export default {
  name: 'app-layout',

  components: {
    SiteHeader,
    SiteFooter
  }
}
</script>
