<template>
  <img :src="url" :alt="t">
</template>
<script>
export default {
  name: 'NImage',
  props: {
    t: String,
    w: [Number, String],
    h: [Number, String],
    fs: [Number, String],
  },
  computed: {
    url() {
      return encodeURI(route('img', _.pickBy(this.$props, p => p)))
    }
  }
}
</script>
