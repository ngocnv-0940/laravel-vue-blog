import store from '~/store'

export default (to, from, next) => {
  if (!store.getters.authCheck || store.getters.authUser.is_admin !== 1) {
    next({ name: 'welcome' })
  } else {
    next()
  }
}
