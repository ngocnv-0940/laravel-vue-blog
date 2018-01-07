import store from '~/store'

export default (to, from, next) => {
  if (store.getters.authCheck) {
    next({ name: 'welcome' })
  } else {
    next()
  }
}
