<template lang="pug">
.wait
  .wait-wrap
    img.splash-img(src="/img/icon512.png")
    p.text-h4 Nuxt
    .pa-16
    loading
</template>

<script>
import loading from '~/items/itemLoading.vue'

export default {
  name: 'splash',
  components: {
    loading: loading,
  },
  data() {
    return {}
  },
}
</script>

<style lang="scss" scoped>
.wait-wrap {
  position: fixed;
  z-index: 999;
  width: 100%;
  height: 100%;
  background-color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-direction: column;
  .splash-img {
    width: 128px;
  }
}
</style>
