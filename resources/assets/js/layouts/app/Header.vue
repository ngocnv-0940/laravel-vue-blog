<template>
  <section>
    <header class="navbar is-transparent is-fixed-top">
      <div class="container">
        <div class="navbar-brand">
          <router-link to="/" exact class="navbar-item" title="Just a blog">
            <img v-if="light" src="" alt="BLAYSKU">
            <img v-else src="" alt="BLAYSKU">
          </router-link>

          <a class="navbar-item" href="#" target="_blank" title="Github">
            <b-icon pack="fa" icon="github"></b-icon>
          </a>

          <a class="navbar-item" href="#" target="_blank" title="Twitter">
            <b-icon pack="fa" icon="twitter"></b-icon>
          </a>

          <span class="navbar-burger burger"
            :class="{ 'is-active': isMenuActive }"
            @click="isMenuActive = !isMenuActive">
          <span></span>
          <span></span>
          <span></span>
        </span>
      </div>

      <div class="navbar-menu" :class="{ 'is-active': isMenuActive }">
        <div class="navbar-start">
          <router-link :to="{ name: 'post.list' }" class="navbar-item">Tin tức</router-link>
          <div class="navbar-item has-dropdown is-hoverable">
            <a class="navbar-link" href="#">
              Docs
            </a>
            <div class="navbar-dropdown is-boxed">
              <a class="navbar-item" href="#">
                Overview
              </a>
              <a class="navbar-item" href="#">
                Modifiers
              </a>
              <hr class="navbar-divider">
              <a class="navbar-item" href="#">
                Elements
              </a>
            </div>
          </div>

          <div class="navbar-item has-dropdown is-hoverable is-mega">
            <div class="navbar-link" :class="{ 'is-active': $route.name == 'post.category' }">
              Chuyên mục
            </div>
            <div id="blogDropdown" class="navbar-dropdown" data-style="width: 18rem;">
              <div class="container is-fluid">
                <div class="columns">
                  <div class="column" v-for="category in categories" :key="category.slug">
                    <h1 class="title is-6 is-mega-menu-title">{{ category.name }}</h1>
                    <router-link :to="{ name: 'post.category', params: { slug: child.slug }}"
                      v-for="child in category.childs"
                      :key="child.slug"
                      class="navbar-item">
                      <div class="navbar-content">
                        <p>{{ child.name }}</p>
                        <p>
                          <small class="has-text-info">03 posts</small>
                        </p>
                      </div>
                    </router-link>
                  </div>
                </div>
              </div>

              <hr class="navbar-divider">
              <div class="navbar-item">
                <div class="navbar-content">
                  <div class="level is-mobile">
                    <div class="level-left">
                      <div class="level-item">
                        <strong>Stay up to date!</strong>
                      </div>
                    </div>
                    <div class="level-right">
                      <div class="level-item">
                        <a class="button bd-is-rss is-small" href="http://bulma.io/atom.xml">
                          <span class="icon is-small">
                            <i class="fa fa-rss"></i>
                          </span>
                          <span>Subscribe</span>
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
        <div class="navbar-end">
          <div class="navbar-item">
            <b-field>
              <b-input placeholder="Tìm kiếm..."
              type="search"
              icon="search">
            </b-input>
          </b-field>
        </div>
        <div class="navbar-item" v-if="authenticated">
          <b-field>
            <router-link :to="{ name: 'post.create' }" class="button is-primary is-outlined">
              <span class="icon">
                <b-icon icon="pencil"></b-icon>
              </span>
              <span>Đăng bài</span>
            </router-link>
          </b-field>
        </div>
        <div class="navbar-item" v-if="!authenticated">
          <a class="button is-outlined"
          :class="light ? 'is-light' : 'is-primary'"
          @click="showLogin = true">
          <b-icon icon="sign-in" pack="fa"></b-icon> <span>Sign in</span>
        </a>
      </div>
      <div class="navbar-item has-dropdown is-hoverable" v-else>
        <router-link :to="{ name: 'user.show', params: { username: user.username }}" exact class="navbar-link">Chào {{ user.name }}!</router-link>
        <div class="navbar-dropdown is-boxed">
          <router-link :to="{ name: 'user.show', params: { username: user.username }}" exact class="navbar-item">Trang cá nhân</router-link>
          <a class="navbar-item" @click.prevent="logout">Đăng xuất</a>
          <hr class="navbar-divider">
          <strong class="navbar-item has-text-grey">NgocBlog v1.0.0</strong>
        </div>
      </div>
      <b-modal :active.sync="showLogin" has-modal-card>
        <login-form></login-form>
      </b-modal>
    </div>
  </div>
</div>
</header>
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
import axios from 'axios'
import { mapGetters, mapState } from 'vuex'
import tabs from './tabs.js'
import LoginForm from '~/pages/auth/LoginForm'

export default {
  props: {
    light: Boolean
  },

  data: () => ({
    tabs,
    appName: window.config.appName,
    isMenuActive: false,
    showLogin: false
  }),

  computed: {
    ...mapGetters({
      user: 'authUser',
      authenticated: 'authCheck',
      currentTab: 'currentTab',
      categories: 'categories'
    }),
    ...mapState(['header'])
  },

  methods: {
    async logout () {
      // Log out the user.
      await this.$store.dispatch('logout')
      // Redirect to login.
      // this.$router.push({ name: 'login' })
    },
    changeTab(tab) {
      this.$router.push({ name: tab.route, params: tab.params || {}})
    }
  },
  created() {
    this.$store.dispatch('getCategories')
  },
  // beforeUpdate(to, from, next) {
  //   console.log(123)
  //   this.$refs.header.isMenuActive = false
  //   this.currentTab = to.meta.category
  //   next()
  // },
  components: {
    LoginForm
  }
}
</script>
