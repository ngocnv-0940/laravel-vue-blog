<template>
  <div class="navbar-item has-dropdown is-hoverable is-active">
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
            :class="{ 'noti-unread': !noti.is_read }"
            @click.native="markAsRead(noti)"
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

        <p class="has-text-centered" v-show="isLoading">
          <i class="fa fa-circle-o-notch fa-spin fa-2x fa-fw"></i>
        </p>
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
                    <i class="fa fa-bell-o"></i>
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
        current_page: 0,
        hasMoreData: true,
        isLoading: false,
      }
    },
    methods: {
      async getNotifications() {
        if (!this.hasMoreData) return
        this.isLoading = true
        this.current_page++
        let { data } = await axios.get(route('user.notifications'), { params: { page: this.current_page }})
        this.notifications = [...this.notifications, ...data.data ]
        this.unread_count = data.unread_count
        if (data.meta.last_page <= this.current_page)
          this.hasMoreData = false
        this.isLoading = false
      },
      async markAsRead(notification) {
        if (notification.is_read) return
        let { data } = await axios.patch(route('user.read-noti'), { notifications: [notification.id]})
        if (data.status) {
          notification.is_read = true
          this.unread_count--
        } else {
          this.$toast.open({
            message: 'Đã có lỗi xảy ra!',
            type: 'is-danger'
          })
        }
      }
    },
    created() {
      this.getNotifications()
    },
    mounted() {
      if (this.hasMoreData) {
        $('.noti-contents').scroll((e) => {
          const _self = $(e.currentTarget)
          if (_self.scrollTop() + _self.innerHeight() == _self[0].scrollHeight) {
            this.getNotifications()
          }
        })
      }

      Echo.private('App.Models.User.' + this.$store.getters.authUser.id)
        .notification((notification) => {
          console.log(notification);
        });
    }
  }
</script>
