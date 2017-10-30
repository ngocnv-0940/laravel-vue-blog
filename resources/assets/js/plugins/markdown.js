import Vue from 'vue'
import marked from 'marked'
import hljs from 'highlight.js'

Vue.component('markdown', {
  template: `<div v-html="marked"></div>`,
  computed: {
    marked() {
      return marked(this.text)
    }
  },
  props: {
    text: {
      type: String,
      required: true
    }
  }
})

marked.setOptions({
  renderer: new marked.Renderer(),
  gfm: true,
  tables: true,
  breaks: false,
  pedantic: false,
  sanitize: true,
  smartLists: true,
  smartypants: false,
  highlight: code => hljs.highlightAuto(code).value
})
