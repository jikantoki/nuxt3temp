<template lang="pug">
.index-page
  .wrap
    v-card.content(elevation="4")
      .text-h1
        span NuxTemp {{ PackageJson.version }}
        img.ontext(src="~/assets/logo.png")
      hr
      .text-h6 Nuxtを簡単に構築できるサンプル
      .btns
        v-btn(@click="getRequest()") 通知を許可する
        v-btn(@click="pushForMe()") 通知送信テスト
        v-btn.is-not-pwa(@click="download('/download/nuxTemp.apk','vuetifyTemplate.apk')") Download APK
        v-btn(@click="a('https://github.com/jikantoki/nuxt3temp')") Github
        v-btn(@click="pop()") ポップアップ
      .input-area
        v-text-field.my-4(label="送りたい通知内容を入力" v-model="notificationText")
      .hgewao {{ $t('page.content') }}
  .wrap
    v-card.content(elevation="4")
      .text-h2 簡単で、美しい。
      hr
      .text NuxTempで理想の作業効率化
      p 吾輩は猫である。名前はまだない。どこで生れたか頓（とん）と見当がつかぬ。何でも薄暗いじめじめした所でニャーニャー泣いていた事だけは記憶している。吾輩はここで始めて人間というものを見た。しかもあとで聞くとそれは書生という人間中で一番獰悪（どうあく）な種族であったそうだ。この書生というのは時々我々を捕（つかま）えて煮て食うという話である。しかしその当時は何という考（かんがえ）もなかったから別段恐しいとも思わなかった。ただ彼の掌（てのひら）に載せられてスーと持ち上げられた時何だかフワフワした感じがあったばかりである。掌の上で少し落ち付いて書生の顔を見たのがいわゆる人間というものの見始（みはじめ）であろう。この時妙なものだと思った感じが今でも残っている。第一毛を以て装飾されべきはずの顔がつるつるしてまるで薬缶（やかん）だ。その後猫にも大分逢（あ）ったがこんな片輪には一度も出会（でく）わした事がない。のみならず顔の真中が余りに突起している。そうしてその穴の中から時々ぷうぷうと烟（けむり）を吹く。どうも咽（む）せぽくて実に弱った。これが人間の飲む烟草（タバコ）というものである事は漸（ようや）くこの頃（ごろ）知った。
  .wrap
    v-card.content(elevation="4")
      .text-h2 画像だって表示可能
      hr
      p このコンポーネントを使えば、エモい感じで画像を簡単に表示できます
      .img-wrap.my-4
        img.big-img(src="~/assets/img001.jpg")
        p.text-h1 テキストを入力
  .wrap
    v-card.content(elevation="4")
      .text-h2 マークダウンぽいやつもお手の物
      hr
      p ノーマルテキスト
  popup(ref="componentPopup")
</template>

<script>
/**
 * ページ推移ごとにmountedを実行する必要があるため、どのviewsでも読み込むこと
 */
import mixins from '~/mixins/mixins'
import webpush from '~/js/webpush'
import componentPopup from '~/components/componentPopup'

export default {
  name: 'index',
  head() {
    return {
      title: 'aaaaa',
      meta: [
        {
          hid: 'description',
          name: 'description',
          content: 'いおえｗんｂをえ',
        },
      ],
    }
  },
  components: {
    popup: componentPopup,
  },
  mixins: [mixins],
  data() {
    return {
      notificationText: '通知テスト12345🤓',
    }
  },
  mounted() {
    const router = useRoute()
    console.log(router)
  },
  methods: {
    getRequest() {
      webpush
        .get(true)
        .then((e) => {
          if (e) {
            this.$refs.componentPopup.pop(
              'ありがとうございます！',
              'プッシュ通知の許可に成功しました。',
              [{ text: 'OK', return: 0 }],
            )
          } else {
            if (e === undefined) {
              this.$refs.componentPopup.pop(
                'リクエスト失敗',
                'ブラウザによって通知へのリクエストが拒否されています。',
                [{ text: 'OK', return: 0 }],
              )
            } else {
              this.$refs.componentPopup.pop(
                'リクエスト失敗',
                `プッシュ通知の許可は、ブラウザから行う必要があります。\nこの端末で <span class="allow-select-all underline">https://${location.host}</span> にアクセスしてください。`,
                [{ text: 'OK', return: 0 }],
              )
            }
          }
        })
        .catch(() => {})
    },
    async pushForMe() {
      const keys = await webpush.get()
      if (!keys) {
        this.$refs.componentPopup.pop(
          '通知を送信できませんでした',
          'プッシュ通知が許可されていないため、処理を完了できませんでした',
          [{ text: 'OK', return: 0 }],
        )
        return false
      }
      this.sendAjax(this.env.VUE_APP_API_HOST + '/sendPushForMe.php', {
        endpoint: keys.endpoint,
        publickey: keys.publicKey,
        authtoken: keys.authToken,
        message: this.notificationText,
      })
        .then((e) => {
          console.log(e)
        })
        .catch((e) => {
          console.log(e)
        })
      return true
    },
    async pop() {
      return await this.$refs.componentPopup
        .pop('テスト表示', 'これはポップアップのサンプルです', [
          { text: 'OK', return: 0 },
          { text: 'NG', return: 1 },
          { text: 'Cancel', return: 2 },
        ])
        .then((e) => {
          console.log(e)
        })
    },
  },
}
</script>

<style lang="scss" scoped></style>
