<template>
  <div class="navbar-item has-dropdown is-hoverable">
    <a class="navbar-item">
      <b-icon
        class="badge"
        :data-badge="unread_count"
        slot="trigger"
        icon="bell-o">
      </b-icon>
    </a>
    <div class="navbar-dropdown is-right navbar-noty">
      <div class="noti-contents">
        <template v-for="noti in notifications">
          <router-link class="navbar-item"
            exactActiveClass=""
            :to="{ name: noti.data.type + '.show', params: { slug: noti.data.slug }}">
            <b-tooltip
              multilined animated
              size="is-large"
              type="is-dark"
              :label="noti.data.text"
              position="is-bottom">
              <div class="navbar-content">
                <p class="noti-content" v-html="noti.data.html"></p>
                <small class="has-text-link has-text-primary">
                  <timeago
                    :since="noti.data.created_at"
                    :autoUpdate="5"
                    :max-time="86400 * 365">
                  </timeago>
                </small>
              </div>
            </b-tooltip>
          </router-link>
          <hr class="navbar-divider">
        </template>
      </div>

      <div class="navbar-item">
        <div class="navbar-content">
          <div class="level is-mobile">
            <div class="level-left">
              <div class="level-item">
                <strong>Xin chào</strong>
              </div>
            </div>
            <div class="level-right">
              <div class="level-item">
                <a class="button bd-is-rss is-small" href="#">
                  <span class="icon is-small">
                    <i class="fa fa-rss"></i>
                  </span>
                  <span>Tất cả thông báo</span>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import axios from 'axios'
  export default {
    data() {
      return {
        notifications: [],
        unread_count: 0,
      }
    },
    methods: {
      async getNotifications() {
        let { data: { data, unread_count }} = await axios.get(route('user.notifications'))
        this.notifications = data
        this.unread_count = unread_count
      }
    },
    created() {
      this.getNotifications()
    },
    mounted() {
      console.log(this.$store.getters.authUser.id)
      Echo.private('App.Models.User.' + this.$store.getters.authUser.id)
        .notification((notification) => {
          console.log(notification);
        });
    }
  }
</script>
