import axios from 'axios'
import * as types from '../mutation-types'
import { LoadingProgrammatic as Loading } from 'buefy'
const Category = () => import('~/pages/categories/CategoryNav.vue')

export const namespaced = true

// state
export const state = {
  title: 'Just a simple blog',
  subtitle: 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quasi quo tempore quis vero atque eos.',
  tabName: 'Chuyên mục',
  tab: Category
}

// getter
export const getters = {

}

// mutations
export const mutations = {

}

// actions
export const actions = {

}
