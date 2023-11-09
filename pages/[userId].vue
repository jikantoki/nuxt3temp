<template lang="pug">
.hwedhgw(v-if="param")
  p Hello! {{ param.userId }}'s Page
  v-btn(@click="console.log($route)") View router
</template>

<script>
import metaFunctions from '~/js/metaFunctions'
import mixins from '~/mixins/mixins'
export default {
  mixins: [mixins],
  data() {
    return {
      param: null,
    }
  },
  mounted() {
    this.param = this.$route.params
    this.setTitle(this.param.userId)
  },
}
</script>
