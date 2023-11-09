<template lang="pug">
.hwedhgw(v-if="param")
  p.text-h3 Hello! {{ param.userId }}
  p(v-if="userData") {{ userData }}
  p(v-if="!userData") unknown user
</template>

<script>
import metaFunctions from '~/js/metaFunctions'
import mixins from '~/mixins/mixins'
export default {
  mixins: [mixins],
  data() {
    return {
      param: null,
      userData: null,
    }
  },
  async mounted() {
    this.param = this.$route.params
    this.setTitle(this.param.userId)
    this.userData = await this.getProfile(this.param.userId)
    console.log(this.userData)
  },
}
</script>
