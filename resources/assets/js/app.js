import Vue from 'vue'
import store from '~/store'
import router from '~/router'
import { i18n } from '~/plugins'
import App from '~/components/App'

import '~/components'

Vue.config.productionTip = false

Vue.mixin({
  methods: {
    chualam() {
      this.$toast.open('Chưa có làm chức năng này nha, ahihi')
    }
  }
})

new Vue({
  i18n,
  store,
  router,
  ...App
})
