import store from '~/store'

export default (to, from, next) => {
  if (store.getters.authUser.is_admin !== 1) {
    next({ name: 'home' })
  } else {
    next()
  }
}
