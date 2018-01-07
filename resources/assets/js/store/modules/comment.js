import axios from 'axios'
import * as types from '../mutation-types'
import { Dialog } from 'buefy'

export const namespaced = true

// state
export const state = {
  comments: [],
  page: 0,
  is_last_page: false,
  loading: false
}

// getter
export const getters = {
}

// mutations
export const mutations = {
  [types.SET_COMMENTS] (state, comments) {
    state.comments = comments
  },

  [types.SET_PAGE] (state, page) {
    state.page = page
  },

  [types.SET_LAST_PAGE_STATUS] (state, status) {
    state.is_last_page = status
  },

  [types.SET_LOADING] (state, loading) {
    state.loading = loading
  },

  [types.ADD_NEW_COMMENT] (state, comment) {
    if (comment.parent_id) {
        let index = state.comments.findIndex(cm => cm.id == comment.parent_id)
        state.comments[index].childs.push(comment)
      } else {
        state.comments.unshift(comment)
      }
  },

  [types.DELETE_COMMENT] (state, { comment, index }) {
    if (!comment.parent_id) {
      state.comments.splice(index, 1)
    } else {
      let parent = state.comments.findIndex(cm => cm.id == comment.parent_id)
      state.comments[parent].childs.splice(index, 1)
    }
  },

  [types.UPDATE_COMMENT] (state, { comment, data }) {
    if (!comment.parent_id) {
      var _comment = state.comments.find(cm => cm.id == comment.id)
    } else {
      let parent = state.comments.find(cm => cm.id == comment.parent_id)
      var _comment = parent.childs.find(cm => cm.id == comment.id)
    }
    _comment = _.extend(_comment, data)
  }
}

// actions
export const actions = {
  async fetchComments({ state, commit, rootState }, { type = 'post', refresh = false }) {
    if (refresh) {
      commit(types.SET_COMMENTS, [])
      commit(types.SET_PAGE, 0)
      commit(types.SET_LAST_PAGE_STATUS, false)
    }

    if (!state.is_last_page) {
      try {
        commit(types.SET_LOADING, true)
        commit(types.SET_PAGE, state.page + 1)
        const params = { slug: rootState.route.params.slug, page: state.page, type: type }
        let { data } = await axios.get(window.route('comment.index', params))

        commit(types.SET_COMMENTS, [...state.comments, ...data.data])
        if (state.page >= data.meta.last_page) {
          commit(types.SET_LAST_PAGE_STATUS, true)
        }
        commit(types.SET_LOADING, false)
      } catch (e) {}
    }
  },
  deleteComment({ dispatch, commit }, { comment, index }) {
    Dialog.confirm({
      message: 'Bạn có muốn xóa bình luận này?',
      cancelText: 'Hủy',
      confirmText: 'Đồng ý',
      onConfirm: async () => {
        let { data: { comments_count }} = await axios.delete(window.route('comment.destroy', comment.id))
        commit(types.DELETE_COMMENT, { comment, index })
        dispatch('article/updateArticle', { comments_count }, { root: true })
      }
    })
  },
  addComment({ commit }, comment) {
    commit(types.ADD_NEW_COMMENT, comment)
  },
  async likeOrUnlikeComment({ commit, state }, comment) {
    const params = { slug: comment.id, type: 'comment' }
    let { data: { action, like }} = await axios.patch(window.route('like.toggle', params))
    let data = {
      likes_count: action == 'like' ? comment.likes_count + 1 : comment.likes_count - 1
    }
    if (comment.parent_id) {
      let parent = state.comments.find(cm => cm.id == comment.parent_id)
      data.likes = _.cloneDeep(parent.childs.find(cm => cm.id == comment.id).likes)
    } else {
      let index = state.comments.findIndex(cm => cm.id == comment.id)
      data.likes = _.cloneDeep(state.comments[index].likes)
    }
    action == 'like' ?
      data.likes.push(like) :
      data.likes.splice(data.likes.findIndex(l => l.id == like.id), 1)
    commit(types.UPDATE_COMMENT, { comment, data })
  }
}
