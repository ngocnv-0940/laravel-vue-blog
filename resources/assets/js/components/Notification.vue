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
      <div class="navbar-item noti-header">
        <div class="navbar-content">
          <div class="level is-mobile">
            <div class="level-left">
              <div class="level-item">
                <span class="icon has-text-black">
                  <i class="fa fa-bell-o"></i>
                </span>
                <strong>Thông báo</strong>
              </div>
            </div>
            <div class="level-right">
              <div class="level-item">
                <a @click.prevent="markAllAsRead" href="#">
                  <small>Đánh dấu tất cả là đã đọc</small>
                </a>
                <small>&nbsp;·&nbsp;</small>
                <a href="#">
                  <small>Cài đặt</small>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="noti-contents">
        <template v-for="noti in notifications">
          <router-link class="navbar-item"
            :class="{ 'noti-unread': !noti.is_read }"
            @click.native="markAsRead(noti)"
            exactActiveClass=""
            :to="{ name: noti.data.type + '.show', params: { slug: noti.data.slug }, hash: noti.data.hash }">
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
      <div class="navbar-item noti-footer">
        <div class="navbar-content has-text-centered">
          <a href="#">
            <small>Tất cả thông báo</small>
          </a>
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
        let { data } = await axios.get(route('notifications'), { params: { page: this.current_page }})
        this.notifications = [...this.notifications, ...data.data ]
        this.unread_count = data.unread_count
        if (data.meta.last_page <= this.current_page)
          this.hasMoreData = false
        this.isLoading = false
      },
      async markAsRead(notification) {
        if (notification.is_read) return
        let { data } = await axios.patch(route('notifications.read'), { notifications: [notification.id]})
        if (data.status) {
          notification.is_read = true
          this.unread_count--
        }
      },
      async markAllAsRead() {
        if (this.unread_count == 0) return
        let { data: { status }} = await axios.patch(route('notifications.read-all'))
        if (status) {
          this.notifications.forEach(noti => {
            if (noti.is_read) return
            noti.is_read = true
          })
          this.unread_count = 0
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
          let newNoti = {
            data: {
              html: notification.html,
              text: notification.text,
              slug: notification.slug,
              type: notification._type,
              created_at: notification.created_at,
            },
            id: notification.id,
            is_read: false,
            type: 'comment_notify'
          }
          this.notifications.unshift(newNoti)
          this.unread_count++
          this.$snackbar.open(notification.text)
        })
    }
  }
</script>
