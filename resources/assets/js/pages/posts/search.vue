<template>
  <div>
    hello {{ query }}
  </div>
</template>
<script>
  export default {
    props: {
      query: String
    }
  }
</script>
