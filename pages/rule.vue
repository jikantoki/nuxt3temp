<template lang="pug">
.index-page
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
      p 現在の言語: {{ $t('page.content') }}
  .wrap
    v-card.content(elevation="4")
      .text-h2 マークダウンぽいやつもお手の物
      hr
</template>

<script>
/**
 * ページ推移ごとにmountedを実行する必要があるため、どのviewsでも読み込むこと
 */
import mixins from '~/mixins/mixins'
import webpush from '~/js/webpush'
import metaFunctions from '~/js/metaFunctions'
export default {
  name: 'index',
  components: {},
  mixins: [mixins],
  data() {
    return {
      notificationText: '通知テスト12345🤓',
      dialog: false,
      dialogTitle: null,
      dialogText: null,
      dialogActions: null,
      counter: useCounterStore(),
    }
  },
  mounted() {
    this.setTitle('トップ')
  },
  methods: {
    getRequest() {
      webpush
        .get(true)
        .then((e) => {
          if (e) {
            this.dialogTitle = 'ありがとうございます！'
            this.dialogText = 'プッシュ通知の許可に成功しました。'
            this.dialogActions = [
              {
                value: '閉じる',
                action: () => {
                  this.dialog = false
                },
              },
            ]
            this.dialog = true
          } else {
            if (e === undefined) {
              this.dialogTitle = 'リクエスト失敗'
              this.dialogText =
                'ブラウザによって通知へのリクエストが拒否されています。'
              this.dialog = true
              this.dialogActions = [
                {
                  value: '閉じる',
                  action: () => {
                    this.dialog = false
                  },
                },
              ]
            } else {
              this.dialogTitle = 'リクエスト失敗'
              this.dialogText = `プッシュ通知の許可は、ブラウザから行う必要があります。\nこの端末で <span class="allow-select-all underline">https://${location.host}</span> にアクセスしてください。`
              this.dialog = true
              this.dialogActions = [
                {
                  value: '閉じる',
                  action: () => {
                    this.dialog = false
                  },
                },
              ]
            }
          }
        })
        .catch(() => {})
    },
    async pushForMe() {
      const keys = await webpush.get()
      if (!keys) {
        this.dialogTitle = '通知を送信できませんでした'
        this.dialogText =
          'プッシュ通知が許可されていないため、処理を完了できませんでした'
        this.dialog = true
        this.dialogActions = [
          {
            value: '閉じる',
            action: () => {
              this.dialog = false
            },
          },
        ]
        return false
      }
      this.sendAjaxWithAuth('/sendPushForMe.php', {
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
      this.dialogTitle = '通知を送信しました'
      this.dialogText = 'プッシュ通知を確認してみてください！'
      this.dialog = true
      this.dialogActions = [
        {
          value: '閉じる',
          action: () => {
            this.dialog = false
          },
        },
      ]
      return true
    },
    createPopup() {
      this.dialogTitle = 'ポップアップテスト'
      this.dialogText = 'これはテストです'
      this.dialog = true
      this.dialogActions = [
        {
          value: 'ボタン2',
          action: () => {
            this.dialog = false
          },
        },
        {
          value: '閉じる',
          action: () => {
            this.dialog = false
          },
        },
      ]
    },
  },
}
</script>

<style lang="scss" scoped></style>
