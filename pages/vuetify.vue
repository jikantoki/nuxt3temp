<template lang="pug">
.home
  //img(alt="Vue logo" src="~/assets/logo.png")
  HelloWorld(msg="Welcome to Your Vue.js App")
</template>

<script>
// @ is an alias to /src
import HelloWorld from '~/items/HelloWorld.vue'
import metaFunctions from '~/js/metaFunctions'
import mixins from '~/mixins/mixins'

export default {
  name: 'HomeView',
  components: {
    HelloWorld,
  },
  mixins: [mixins],
  mounted() {
    this.setTitle('Vuetify')
  },
}
</script>

<style lang="scss" scoped>
.home {
  text-align: center;
}
</style>
