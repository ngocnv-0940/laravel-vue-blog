import axios from 'axios'
import tabs from '~/layouts/app/tabs'

// state
export const state = {
  tab: null,
  title: null,
  subtitle: null,
  categories: []
}

// getter
export const getters = {
  currentTab: state => tabs.find(tab => tab.name == state.tab),
  categories: state => state.categories
}

// mutations
export const mutations = {
  setTab(state, tab) {
    state.tab = tab
  },
  setTitle(state, { title, subtitle }) {
    state.title = title
    state.subtitle = subtitle
  },
  setCategories(state, categories) {
    state.categories = categories
  }
}

// actions
export const actions = {
  async getCategories({ commit }) {
    const { data: { data }} = await axios.get(window.route('category.index'))
    commit('setCategories', data)
  }
}
