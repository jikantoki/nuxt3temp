<template lang="pug">
.hwedhgw(v-if="param")
  p.text-h3 Hello! {{ param.userId }}
  div(v-if="userData")
    p {{ userData }}
    v-btn(@click="sendPushForAccount(userData.userId)") {{ userData.userId }}に通知を送信
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
    const userId = route.params.userId
    //サーバーサイドで仮のタイトルを設定、mountedで言語ごとに再設定する
    Setup.setTitle(userId)
    Setup.setDescription(`${userId}さんの詳細ページです`)
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
  methods: {
    sendPushForAccount(userId) {
      this.sendAjaxWithAuth(
        '/sendPushForAccount.php',
        {
          for: userId,
        },
        {
          title: '通知テスト',
          message: 'うんち',
        },
      )
        .then((e) => {
          console.log(e)
        })
        .catch((e) => {
          console.log(e)
        })
    },
  },
}
</script>
