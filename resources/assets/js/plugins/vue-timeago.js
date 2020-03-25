import Vue from 'vue'
import VueTimeago from 'vue-timeago'

Vue.use(VueTimeago, {
  name: 'timeago',
  locale: 'vi-VN',
  locales: {
    'vi-VN': require('vue-timeago/locales/vi-VN.json')
  }
})
