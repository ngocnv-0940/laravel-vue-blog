<template>
  <div>
    <site-header/>
    <site-hero/>
    <section class="section">
      <transition name="slide-fade">
        <child/>
      </transition>
    </section>
    <site-footer/>
  </div>
</template>

<script>
import SiteHeader from './app/Header'
import SiteFooter from './app/Footer'
import SiteHero from './app/Hero'

export default {
  name: 'app-layout',

  components: {
    SiteHeader,
    SiteFooter,
    SiteHero
  }
}
</script>
