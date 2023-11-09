/**
 * ここに記述したものはVueの機能として使える
 * しかもどのファイルでも読み込める
 */

//import router from '~/router/router'
import adsense from '~/components/common/commonAdsense'
import ajaxFunctions from '~/js/ajaxFunctions'
import PackageJson from '/package.json'
import Functions from '~/js/Functions'

export default {
  components: {
    adsense: adsense,
  },
  data() {
    return {
      cookieAllowed: false,
      PackageJson: PackageJson,
      envconf: useRuntimeConfig(),
      env: null,
      counter: useCounterStore(),
      metaStore: useMetaStore(),
      userStore: useUserStore(),
    }
  },
  mounted() {
    const isAllow = localStorage.cookieAllowed === 'true'
    this.cookieAllowed = isAllow
    const vues = this
    const aTags = document.querySelectorAll('a')
    for (let count = 0; count < aTags.length; count++) {
      if (aTags[count].href !== '') {
        aTags[count].onclick = function () {
          const now = new URL(window.location.href).host
          const next = new URL(aTags[count].href).host
          let to = aTags[count].href
          if (now === next) {
            const next = new URL(aTags[count].href)
            to = next.pathname + next.hash + next.search
          }
          vues.a(to)
          event.preventDefault()
          return false
        }
      }
    }
    this.env = this.envconf.public.env
  },
  methods: {
    sendAjax: ajaxFunctions.send,
    /**
     * APIトークンを同時に送信するAjax（内部処理用）
     * @param {string} url 送信先URL（ドメインは自動で付きます）
     * @param {object} sendObject 送りたいオブジェクト
     */
    sendAjaxWithAuth(url, sendObject) {
      const auth = {
        apiid: this.env.VUE_APP_API_ID,
        apitoken: this.env.VUE_APP_API_TOKEN,
        apipassword: this.env.VUE_APP_API_ACCESSKEY,
      }
      const sendObj = Object.assign(auth, sendObject)
      return this.sendAjax(this.env.VUE_APP_API_HOST + url, sendObj)
    },
    /**
     * <p>aタグと同じ動きをするし、pjaxになる</p>
     * <p>外部URLの場合、新しいタブで開く</p>
     * @param {string} url 転送したいURL（ルートからのパス）
     * @returns {int} 内部リンクなら0、外部ドメインなら1
     */
    a(url) {
      if (url.slice(0, 4) === 'http') {
        window.open(url, '_blank')
        return 1
      } else {
        this.$router.push(url)
        if (this.drawer) this.drawer = false
        return 0
      }
    },
    /**
     * ファイルをダウンロードさせる
     * @param {string} filePath ファイルのパス
     * @returns 0
     */
    download(filePath, name) {
      if (!name) {
        name = filePath
      }
      let element = document.createElement('a')
      element.href = filePath
      element.download = name
      element.target = '_blank'
      element.click()
      return 0
    },
    /**
     * 0未満なら-1で乗算する
     * @param {int, float} number 変換したい数値
     * @returns 正の値
     */
    unsigned(number) {
      if (number < 0) {
        return number * -1
      } else {
        return number
      }
    },
    /**
     * クッキーの特定のキーを取得
     * @param {string} name 取得したいCookieのキー
     * @returns キーがあればvalue、無ければnull
     */
    getCookie(name) {
      let c = new RegExp(name + '=[^;]+').exec(document.cookie)
      return c ? c[0].replace(name + '=', '') : null
    },
    /**
     * 全てのクッキーを連想配列で返す
     * @returns cookie
     */
    getAllCookie() {
      let cookie = document.cookie
      if (!cookie || cookie === '') {
        return null
      }
      let cookieArray = cookie.split(';')
      let newCookieArray = []
      for (const keyAndValue of cookieArray) {
        let keyValue = keyAndValue.split('=')
        newCookieArray.push(keyValue)
      }
      return newCookieArray
    },
    /**
     * クッキーをセットする
     * @param {string} key 設定したいキー
     * @param {*} value 設定したい値
     * @returns OKだったらTrue、許可がなかったらFalse
     */
    setCookie(key, value) {
      if (this.cookieAllowed) {
        document.cookie = `${key}=${value};`
        return true
      } else {
        return false
      }
    },
    checkCookie() {
      let isAllow = localStorage.cookieAllowed
      isAllow = isAllow ? true : false
      this.cookieAllowed = isAllow
      return isAllow
    },
    /**
     * Cookieを許可する
     */
    allowCookie() {
      localStorage.cookieAllowed = true
      this.cookieAllowed = true
    },
    /**
     * Cookieを拒否する
     */
    denyCookie() {
      localStorage.cookieAllowed = false
      this.cookieAllowed = false
    },
    /**
     * いい感じのタイトルを付ける
     * @param {string} newTitle 新しく付けたいタイトル
     * @returns 引数に合わせて設定したら0、デフォルトのまま設定したら1
     */
    setTitle: (newTitle) => {
      let siteName = PackageJson.name
      siteName = Functions.ifEnglishStartUpper(siteName)
      let pageTitle
      let returnCode
      if (newTitle) {
        pageTitle = `${newTitle} | ${siteName}`
        returnCode = 0
      } else {
        pageTitle = siteName
        returnCode = 1
      }
      document.title = pageTitle
      useMetaStore().setTitle(newTitle)
      return returnCode
    },

    //ここからは優先度低いやつ

    /**
     * 変数が使われてません！を無効化
     * @param {*} obj エラーを無効化したい変数
     * @returns objがtrueなら1
     */
    nulling(obj) {
      if (obj) return 1
    },
    crack() {
      alert('さてはオメー、ソースコードを見ているな！？！？！？')
      return 7095110
    },
  },
}
