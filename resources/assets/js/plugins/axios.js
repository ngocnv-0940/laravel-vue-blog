import axios from 'axios'
import store from '~/store'
import router from '~/router'
import { Toast } from 'buefy'
import i18n from './vue-i18n'

axios.defaults.baseURL = '/api/'

axios.interceptors.request.use(request => {
  if (store.getters.authToken) {
    request.headers.common['Authorization'] = `Bearer ${store.getters.authToken}`
  }

  // request.headers['X-Socket-Id'] = Echo.socketId()

  return request
})

axios.interceptors.response.use(response => response, error => {
  const { status, data: { message } } = error.response
  if (status === 404) {
    Toast.open({
      message: i18n.t('page_not_found'),
      type: 'is-danger'
    })
    router.replace('/not-found')
  } else if (status === 403) {
    Toast.open({
      message: 'Bạn không có quyền truy cập trang này!',
      type: 'is-danger'
    })
    router.replace('/')
  } else if (status === 401 && store.getters.authCheck) {
    Toast.open({
      message: i18n.t('token_expired_alert_text'),
      type: 'is-warning'
    })
    .then(async () => {
      await store.dispatch('logout')

      router.push({ name: 'login' })
    })
  } else if (status >= 500) {
    Toast.open({
      message: i18n.t('error_alert_text'),
      type: 'is-danger'
    })
  }

  return Promise.reject(error)
})
