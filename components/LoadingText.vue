<template lang="pug">
ContentLoader.loading-text(:width="width")
</template>

<script>
import { ContentLoader } from 'vue-content-loader'
export default {
  props: {
    width: {
      type: String,
      require: false,
      default: '10em',
    },
  },
  components: {
    ContentLoader,
  },
}
</script>

<style lang="scss" scoped>
.loading-text {
  border-radius: 0.3em;
  max-width: 100%;
  display: block;
}
</style>
