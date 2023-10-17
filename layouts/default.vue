<template lang="pug">
.v-app-main-application
  splash(v-show="splash")
  v-app.wrap100vh#nuxt(ontouchstart="" style="min-height: 100vh!important;width:100vw" :style="style")
    header
      common-header
    v-main#main
      .center
        router-view
        common-cookie-get-permission
        popup(ref="componentPopup")
      v-footer.pa-16#footer
        common-footer
  .right-space(style="min-height: 100vh")
</template>

<script>
import PackageJson from '/package.json'
import Functions from '~/js/Functions'
import commonHeader from '~/components/common/commonHeader'
import commonFooter from '~/components/common/commonFooter'
import commonCookieGetPermission from '~/components/common/commonCookieGetPermission'
import componentPopup from '~/components/componentPopup'
import mixins from '~/mixins/mixins'
import webpush from '~/js/webpush'
import splash from '~/components/common/commonSplash'

export default {
  /**
   * Appが定番
   */
  name: 'App',
  /**
   * 使いたいvueファイルを記述
   */
  components: {
    commonHeader: commonHeader,
    commonFooter: commonFooter,
    commonCookieGetPermission: commonCookieGetPermission,
    popup: componentPopup,
    splash: splash,
  },
  mixins: [mixins],
  /**
   * このファイル内で使うthisで始まる変数
   */
  data() {
    /**
     * この中に書かないと使えない
     */
    return {
      splash: true,
      style: 'opacity: 0;',
    }
  },
  /**
   * 監視したい変数を記述
   */
  watch: {},
  /**
   * ページ生成時にやりたい事
   */
  mounted() {
    PackageJson.name = Functions.ifEnglishStartUpper(PackageJson.name)
    /*
      this.sendAjax('/api/test/object.html', {
        goodbye: 'バイバ～イ!yeah',
        sayMeow: 'みゃお'
      })
        .then((value) => {
          console.log(value)
        })
        .catch((e) => {
          console.warn(e)
        })
      this.sendAjax('/api/test/string.html')
        .then((value) => {
          console.log(value)
        })
        .catch((e) => {
          console.warn(e)
        })
      */
    webpush
      .set()
      .then((e) => {
        console.log(e)
      })
      .catch((e) => {
        console.log(e)
      })

    /**
     * mountedの最後に記述
     */
    this.splash = false
    this.style = 'opacity: 1;'
  },
  /**
   * ページ離脱時にやりたい事
   */
  unmouonted() {},
  /**
   * このファイル内で使いたい変数
   */
  methods: {},
}
</script>

<style lang="scss">
$breakpoints: (
  'smartPhone': 'screen and (max-width:900px)',
  'tablet': 'screen and (max-width:1400px)',
  'pwa': '(display-mode: standalone)',
) !default;

/* フォント設定 */
$font: 'Zen Maru Gothic', sans-serif;
@import url('https://fonts.googleapis.com/css2?family=Zen+Maru+Gothic&display=swap');
/* フォント設定ここまで */

@mixin mq($breakpoint) {
  @media #{map-get($breakpoints, $breakpoint)} {
    @content;
  }
}
$body-font-family: $font;
html,
body {
  height: 100vh !important;
}
:root {
  font-size: 16px;
  --color-allow: #cceeff;
  --color-error: #ffcccc;
  --color-error: #cc2222;
  /** アプリの色 */
  --accent-color: #00bbee;
  /** アプリの色に合わせた文字色 */
  --accent-text-color: #ffffff;
  /** デフォルトのボーダー角の大きさ */
  --border-radius: 16px;
}
* {
  user-select: none;
  list-style: none;
  transition: all 0.14s;
  font-family: $font !important;
}
#nuxt {
  font-family: $font !important;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  color: #2c3e50;
  nav {
    padding: 30px;
  }
  .center {
    width: 60%;
    margin: auto;
    @include mq('tablet') {
      width: 80%;
    }
    @include mq('smartPhone') {
      width: 100%;
    }
  }
  .text-h0 {
    font-size: 4em !important;
  }
  .text-h1 {
    font-size: 3em !important;
  }
  .text-h2 {
    font-size: 2.8em !important;
  }
  .text-h3 {
    font-size: 2.4em !important;
  }
  .text-h4 {
    font-size: 2.2em !important;
  }
  .text-h5 {
    font-size: 2em !important;
  }
  .text-h6,
  .text {
    font-size: 1.5em !important;
  }
  .text-h7,
  .text-small {
    font-size: 1em !important;
  }
  .text-h0,
  .text-h1,
  .text-h2,
  .text-h3 {
    white-space: nowrap;
    overflow: hidden;
  }
  .text-h0,
  .text-h1,
  .text-h2,
  .text-h3,
  .text-h4,
  .text-h5,
  .text-h6,
  .text-h7,
  .text-small {
    font-family: $font !important;
    margin-top: 0.2em;
    margin-bottom: 0.2em;
  }
  .splash-text {
    font-family: $font !important;
  }
  .allow-select {
    user-select: auto;
  }
  .allow-select-all {
    user-select: all;
  }
  .underline {
    text-decoration: underline;
  }
  .v-btn {
    text-transform: none;
  }
  button {
    margin: 0.5em;
  }
  .content {
    width: 95%;
    display: flex;
    flex-direction: column;
    padding: 8px 16px;
    border-radius: var(--border-radius);
    overflow: hidden;
  }
  .wrap {
    display: flex;
    justify-content: center;
    padding: 8px 0px;
    flex-direction: column;
    align-items: center;
  }
  .wrap100vh {
    min-height: 100vh !important;
  }
  .ontext {
    height: 1em;
    vertical-align: middle;
    object-fit: cover;
  }
  hr {
    margin: 8px auto;
    width: 98%;
    align-items: center;
    border: 1px solid;
    opacity: 0.3;
  }
  /** モバイル用表示 */
  .is-mobile {
    display: none;
    @include mq('smartPhone') {
      display: initial;
    }
  }
  /** デスクトップ用表示 */
  .is-not-mobile {
    display: initial;
    @include mq('smartPhone') {
      display: none;
    }
  }
  /** モバイル用表示 */
  .is-pwa {
    display: none;
    @include mq('pwa') {
      display: initial;
    }
  }
  /** PWAじゃない場合に表示 */
  .is-not-pwa {
    display: initial;
    @include mq('pwa') {
      display: none;
    }
  }
  .big-img {
    max-height: 300px;
    object-fit: cover;
    width: 100%;
  }
  .img-wrap {
    position: relative;
    background-color: #000000;
    p {
      position: absolute;
      top: 50%;
      left: 50%;
      -ms-transform: translate(-50%, -50%);
      -webkit-transform: translate(-50%, -50%);
      transform: translate(-50%, -50%);
      margin: 0;
      padding: 0;
      color: white;
      white-space: nowrap;
    }
    img {
      opacity: 0.7;
    }
  }
  #main {
    position: relative;
    #footer {
      position: absolute;
      bottom: 0;
      width: 100%;
    }
  }
  .relative {
    position: relative;
  }
  .absolute {
    position: absolute;
  }
}
</style>
