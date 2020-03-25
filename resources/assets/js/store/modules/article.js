import axios from 'axios'
import * as types from '../mutation-types'
import { LoadingProgrammatic as Loading } from 'buefy'

export const namespaced = true

// state
export const state = {
  article: {
    author: {},
    category: {},
    likes: [],
    content: ''
  }
}

// getter
export const getters = {
  hasLiked: (state, getters, rootState, rootGetters) => {
    return rootGetters.authCheck ? state.article.likes.some(like => like.user_id == rootState.auth.user.id) : false
  }
}

// mutations
export const mutations = {
  [types.SET_ARTICLE] (state, article) {
    state.article = article
  },

  [types.UPDATE_ARTICLE] (state, data) {
    state.article = _.extend(state.article, data)
  }
}

// actions
export const actions = {
  async fetchArticle({ commit, rootState }, route) {
    const loading = Loading.open()
    try {
      let { data: { data }} = await axios.get(window.route(route, rootState.route.params.slug))
      commit(types.SET_ARTICLE, data)
      commit('setTitle', { title: data.title, subtitle: data.category.name }, { root: true })
    } catch (e) {}
    loading.close()
  },

  updateArticle({ commit }, data) {
    commit(types.UPDATE_ARTICLE, data)
  },

  async likeOrUnlike({ state, commit, rootState }, type) {
    const params = { slug: rootState.route.params.slug, type: type }
    let { data: { like, action }} = await axios.patch(window.route('like.toggle', params))
    if (action == 'like') {
      let article = _.cloneDeep(state.article)
      article.likes.push(like)
      article.likes_count++
      commit(types.SET_ARTICLE, article)
    } else if (action == 'unlike') {
      let likeIndex = state.article.likes.findIndex(l => l.id == like.id)
      let article = _.cloneDeep(state.article)
      article.likes.splice(likeIndex, 1)
      article.likes_count--
      commit(types.SET_ARTICLE, article)
    }
  }
}
