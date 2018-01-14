<template>
  <header class="navbar is-fixed-top"
    :class="light ? 'is-primary' : ''">
    <div class="container">
      <div class="navbar-brand">
        <router-link to="/" exact class="navbar-item" title="Blaysku - Nguyen Van Ngoc's Blog">
          <img v-if="light" src="/uploads/images/doraemon.png" alt="BLAYSKU">
          <img v-else src="/uploads/images/doraemon.png" alt="BLAYSKU">
        </router-link>
<!--         <router-link :to="{ name: 'post.create' }" class="navbar-item" v-if="authenticated && isMenuActive"
          :class="light ? 'is-light' : 'is-primary'">
          <span class="icon">
            <b-icon icon="pencil"></b-icon>
          </span>
        </router-link> -->
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
          <div class="navbar-item has-dropdown is-hoverable is-mega" v-show="!isMenuActive">
            <div class="navbar-link" :class="{ 'is-active': $route.name == 'post.category' }">
              Chuyên mục
            </div>
            <div class="navbar-dropdown">
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
                          <small class="has-text-primary">{{ child.posts_count }} bài viết</small>
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
                        <strong>Tất cả chuyên mục</strong>
                      </div>
                    </div>
                    <div class="level-right">
                      <div class="level-item">
                        <a class="button bd-is-rss is-small" href="#">
                        <span class="icon is-small">
                        <i class="fa fa-rss"></i>
                        </span>
                        <span>Hello world</span>
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
                size="is-rounded"
                @input.native.once="chualam"
                icon="search">
              </b-input>
            </b-field>
          </div>
          <template v-if="authenticated">
            <div class="navbar-item">
              <b-field>
                <router-link :to="{ name: 'post.create' }" class="button is-outlined"
                  :class="light ? 'is-light' : 'is-primary'">
                  <span class="icon">
                    <b-icon icon="pencil"></b-icon>
                  </span>
                  <span>Đăng bài</span>
                </router-link>
              </b-field>
            </div>
            <notification></notification>
            <div class="navbar-item has-dropdown is-hoverable">
              <router-link :to="{ name: 'user.show', params: { username: user.username }}" exact class="navbar-link">{{ user.username }}!</router-link>
              <div class="navbar-dropdown">
                <router-link :to="{ name: 'user.show', params: { username: user.username }}" exact class="navbar-item">Trang cá nhân</router-link>
                <a class="navbar-item" @click.prevent="logout">Đăng xuất</a>
                <hr class="navbar-divider">
                <strong class="navbar-item has-text-grey">NgocBlog 0.1</strong>
              </div>
            </div>
          </template>
          <div class="navbar-item" v-else>
            <a class="button is-outlined"
              :class="light ? 'is-light' : 'is-primary'"
              @click="showLogin = true">
              <b-icon icon="sign-in" pack="fa"></b-icon>
              <span>Đăng nhập</span>
            </a>
          </div>
          <b-modal :active.sync="showLogin" has-modal-card>
            <login-form></login-form>
          </b-modal>
        </div>
      </div>
    </div>
  </header>
</template>
<script>
  import axios from 'axios'
  import { mapGetters } from 'vuex'
  import LoginForm from '~/pages/auth/LoginForm'
  import Notification from '~/components/Notification'
  import HeroBar from './Hero'

  export default {
    props: {
      light: Boolean
    },

    data: () => ({
      isMenuActive: false,
      showLogin: false
    }),

    computed: {
      ...mapGetters({
        user: 'authUser',
        authenticated: 'authCheck',
        categories: 'categories'
      })
    },

    methods: {
      async logout () {
        // Log out the user.
        await this.$store.dispatch('logout')
        // Redirect to home.
        this.$router.push({ name: 'welcome' })
      }
    },
    created() {
      this.$store.dispatch('getCategories')
    },
    components: {
      LoginForm,
      Notification
    }
  }
</script>
