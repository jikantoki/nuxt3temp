<template lang="pug">
.hwedhgw(v-if="param")
  p.text-h3 Hello! {{ param.userId }}
  div(v-if="userData")
    p {{ userData }}
    v-btn(@click="setTitle(userData.userId)") {{ userData.userId }}
  p(v-if="!userData") unknown user
</template>

<script>
import metaFunctions from '~/js/metaFunctions'
import mixins from '~/mixins/mixins'
import Setup from '~/js/setup'
export default {
  mixins: [mixins],
  setup() {
    const route = useRoute()
    //サーバーサイドで仮のタイトルを設定、mountedで言語ごとに再設定する
    Setup.setTitle(route.params.userId)
  },
  data() {
    return {
      param: null,
      userData: null,
    }
  },
  async mounted() {
    this.param = this.$route.params
    this.userData = await this.getProfile(this.param.userId)
    if (this.userData) {
      this.setTitle(this.userData.userId)
    } else {
      this.setTitle('unknown user')
    }
    console.log(this.userData)
  },
}
</script>
