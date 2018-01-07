<template>
  <section>
    <div class="hero is-primary">
      <div class="hero-body">
        <div class="container">
          <h1 class="title">{{ header.title || `Blaysku's blog` }}</h1>
          <h2 class="subtitle">{{ header.subtitle || 'Lorem ipsum dolor sit amet.' }}</h2>
        </div>
      </div>
      <div class="hero-foot">
        <div class="container">
          <nav class="tabs is-boxed">
            <ul>
              <li v-for="tab in tabs"
                v-if="tab.title"
                :key="tab.title"
                :class="{ 'is-active': currentTab && currentTab.name === tab.name }">
                <a @click="changeTab(tab)">
                {{ tab.title }}
                </a>
              </li>
            </ul>
          </nav>
        </div>
      </div>
    </div>
    <nav class="navbar has-shadow">
      <div class="container">
        <keep-alive>
          <component v-if="currentTab" :is="currentTab.component"></component>
        </keep-alive>
      </div>
    </nav>
  </section>
</template>
<script>
  import tabs from './tabs.js'
  import { mapGetters, mapState } from 'vuex'
  export default {
    data() {
      return {
        tabs
      }
    },
    computed: {
      ...mapGetters(['currentTab', 'categories']),
      ...mapState(['header'])
    },
    methods: {
      changeTab(tab) {
        if (tab.name == 'category') {
          let slug = _.get(this.categories, '0.childs[0].slug')
          tab.params = { slug }
        }
        this.$router.push({ name: tab.route, params: tab.params || {}})
      }
    }
  }
</script>
