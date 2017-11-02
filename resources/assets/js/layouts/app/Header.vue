<template>
  <section>
    <header class="navbar is-transparent">
        <div class="container">
            <div class="navbar-brand">
                <router-link to="/" exact class="navbar-item" title="Buefy: lightweight UI components for Vue.js based on Bulma">
                    <img v-if="light" src="https://buefy.github.io/static/img/buefy-light.7df103a.png" alt="Buefy">
                    <img v-else src="https://buefy.github.io/static/img/buefy.1d65c18.png" alt="Buefy">
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
                            <b-icon icon="create"></b-icon>
                          </span>
                          <span>Đăng bài</span>
                        </router-link>
                      </b-field>
                    </div>
                    <div class="navbar-item" v-if="!authenticated">
                        <a class="button is-outlined"
                            :class="light ? 'is-light' : 'is-twitter'"
                            @click="showLogin = true">
                            <b-icon icon="sign-in" pack="fa"></b-icon> <span>Sign in</span>
                        </a>
                    </div>
                    <div class="navbar-item has-dropdown is-hoverable" v-else>
                        <router-link to="/" exact class="navbar-link">Chào {{ user.name }}!</router-link>
                        <div class="navbar-dropdown is-boxed">
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
                <h1 class="title">Lorem ipsum dolor sit amet.</h1>
                <h2 class="subtitle">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Laborum, ut.</h2>
            </div>
        </div>
        <div class="hero-foot">
            <div class="container">
                <nav class="tabs is-boxed">
                    <ul>
                        <li v-for="tab in tabs"
                            :key="tab.title"
                            :class="{ 'is-active': currentTab === tab.component }">
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
            <component :is="currentTab"></component>
          </keep-alive>
        </div>
    </nav>
  </section>
</template>

<script>
import { mapGetters } from 'vuex'
import tabs from './tabs.js'
import Post from '../../pages/posts/PostNav'
import LoginForm from '~/pages/auth/LoginForm'

export default {
  props: {
    light: Boolean
  },

  data: () => ({
    currentTab: Post,
    tabs,
    appName: window.config.appName,
    isMenuActive: false,
    showLogin: false
  }),

  computed: mapGetters({
    user: 'authUser',
    authenticated: 'authCheck'
  }),

  methods: {
    async logout () {
      // Log out the user.
      await this.$store.dispatch('logout')

      // Redirect to login.
      // this.$router.push({ name: 'login' })
    },
    tweet() {
      const width = 575
      const height = 400
      const left = (window.screen.width - width) / 2
      const top = (window.screen.height - height) / 2
      const url = `https://twitter.com/share?url=${encodeURIComponent(document.location.protocol + '//' + document.location.host)}&text=Buefy: lightweight UI components for Vue.js based on Bulma&hashtags=buefy&via=rafaelpimpa`
      const opts = `status=1,width=${width},height=${height},top=${top},left=${left}`

      window.open(url, '', opts)
    },
    changeTab(tab) {
      this.currentTab = tab.component
      this.$router.push({ name: tab.route, params: tab.params || {}})
    }
  },
  beforeRouteUpdate(to, from, next) {
    console.log(123)
    this.$refs.header.isMenuActive = false
    this.currentTab = to.meta.category
    next()
  },
  beforeRouteEnter(to, from, next) {
    next(vm => {
      vm.currentTab = to.meta.category
    })
  },
  components: {
    LoginForm
  }
}
</script>
