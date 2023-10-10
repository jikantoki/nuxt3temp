<template lang="pug">
.header
  v-app-bar
    template(v-slot:append)
      v-btn(icon="mdi-magnify")
      v-btn(icon="mdi-dots-vertical")
    v-app-bar-nav-icon(v-if="isRoot" @click="toggleDrawer()")
    v-btn(v-if="!isRoot" icon="mdi-keyboard-backspace" @click="back()")
    v-app-bar-title {{ $route.meta.title }}
  v-navigation-drawer.pa-0(v-model="drawer" fixed temporary)
    v-list(nav dense)
      v-item-group(v-model="group" active-class="deep-purple-text text--accent-4")
        a.header-list(:href="'/login'")
          v-list-item.pa-4(link)
            .v-item
              v-icon mdi-account-outline
              p.nav ログイン
        v-divider(style="opacity:0.3")
        a.header-list(v-for="navigationItem in NavigationList" :href="navigationItem.url")
          v-list-item.pa-4(link)
            .v-item
              v-icon(style="opacity:0.7") {{ navigationItem.icon }}
              p.nav {{ navigationItem.name }}
        v-divider(style="opacity:0.3")
        a.header-list(v-for="navigationItem in infoList" :href="navigationItem.url")
          v-list-item.pa-4(link)
            .v-item
              v-icon(style="opacity:0.7") {{ navigationItem.icon }}
              p.nav {{ navigationItem.name }}
        v-divider(style="opacity:0.3")
        v-list-item.pa-4
          .v-item
            v-icon(style="opacity:0.7") mdi-theme-light-dark
            p.nav Theme
            v-switch(v-model="isDarkTheme")
</template>

<script>
import Functions from '~/js/Functions'
import MetaFunctions from '~/js/metaFunctions'
import NavigationList from '~/items/itemNavigationList'
import infoList from '~/items/itemInfoList'
//import router, { currentMeta } from '~/router/router'
import mixins from '~/mixins/mixins'
export default {
  components: {},
  mixins: [mixins],
  data() {
    return {
      NavigationList: NavigationList,
      infoList: infoList,
      Functions: Functions,
      //currentMeta: currentMeta,
      drawer: false,
      group: 1,
      isRoot: false,
      theme: 'light',
      isDarkTheme: false,
    }
  },
  watch: {
    $route() {
      const now = new URL(window.location.href)
      if (Functions.isRoot(now.pathname)) {
        this.isRoot = true
      } else {
        this.isRoot = false
      }
    },
    isDarkTheme(isDark) {
      if (isDark) {
        this.$vuetify.theme.dark = true
        MetaFunctions.setStatusColor('#222222')
        localStorage.theme = 'dark'
      }
      if (!isDark) {
        this.$vuetify.theme.dark = false
        MetaFunctions.setStatusColor('#FFFFFF')
        localStorage.theme = 'light'
      }
    },
  },
  mounted() {
    const now = new URL(window.location.href)
    if (Functions.isRoot(now.pathname)) {
      this.isRoot = true
    } else {
      this.isRoot = false
    }
    /*
    if (this.$vuetify.theme.dark === 'light' && this.isDarkTheme) {
      this.$vuetify.theme.dark = 'dark'
      MetaFunctions.setStatusColor('#222222')
      localStorage.theme = 'dark'
    }
    if (this.$vuetify.theme.dark === 'dark' && !this.isDarktheme) {
      this.$vuetify.theme.dark = 'light'
      MetaFunctions.setStatusColor('#FFFFFF')
      localStorage.theme = 'light'
    }
    */
    if (window.matchMedia('(prefers-color-scheme: dark)').matches == true) {
      //dark mode
      this.$vuetify.theme.dark = true
      MetaFunctions.setStatusColor('#222222')
      this.isDarkTheme = true
    } else {
      this.$vuetify.theme.dark = false
      MetaFunctions.setStatusColor('#FFFFFF')
      this.isDarkTheme = false
    }

    const theme = localStorage.theme
    if (theme) {
      switch (theme) {
        case 'light':
          this.$vuetify.theme.dark = false
          MetaFunctions.setStatusColor('#FFFFFF')
          this.isDarkTheme = false
          break
        case 'dark':
          this.$vuetify.theme.dark = true
          MetaFunctions.setStatusColor('#222222')
          this.isDarkTheme = true
          break
      }
    }

    //タップ時の誤動作を防ぐためのスワイプ時の処理を実行しない最小距離
    const minimumDistance = 10
    //スワイプ開始時の座標
    let startX = 0
    let startY = 0
    //スワイプ終了時の座標
    let endX = 0
    let endY = 0
    this.nulling(endY)

    //移動を開始した座標を取得
    document.getElementById('main').addEventListener(
      'touchstart',
      (e) => {
        startX = e.touches[0].clientX
        startY = e.touches[0].clientY
      },
      { passive: true },
    )

    //移動した座標を取得
    document.getElementById('main').addEventListener(
      'touchmove',
      (e) => {
        endX = e.changedTouches[0].clientX
        endY = e.changedTouches[0].clientY
      },
      { passive: true },
    )

    //移動距離から左右or上下の処理を実行
    document.getElementById('main').addEventListener('touchend', (e) => {
      //触っているクラスに.dont-swipeが含まれていたらリジェクト
      const classes = e.target.className.split(' ')
      if (classes) {
        for (const className of classes) {
          if (className === 'dont-swipe') {
            return false
          }
        }
      }
      // スワイプ終了時にx軸とy軸の移動量を取得
      // 左スワイプに対応するためMath.abs()で+に変換
      const distanceX = this.unsigned(endX - startX)
      const distanceY = this.unsigned(endY - startY)

      //左右のスワイプ距離の方が上下より長い && 小さなスワイプは検知しないようにする
      if (distanceX > distanceY && distanceX > minimumDistance) {
        //スワイプ後の動作
        if (endX - startX > 0) {
          //rightswipe
          this.drawer = true
        }
      }

      //上下のスワイプ距離の方が左右より長い && 小さなスワイプは検知しないようにする
      if (distanceX < distanceY && distanceY > minimumDistance) {
        //スワイプ後の動作
        //console.log('上下スワイプ')
      }
    })
  },
  methods: {
    toggleDrawer() {
      if (this.drawer === false) {
        this.drawer = true
      } else {
        this.drawer = false
      }
    },
    toggleTheme() {
      if (this.isDarkTheme) {
        this.isDarkTheme = false
      } else {
        this.isDarkTheme = true
      }
    },
    /**
     * 同じドメイン、かつindexじゃない場所にいる場合はページを戻す
     * @param {bool} PleaseReturn trueの場合、ページを戻せない場合にreturnする
     */
    back(pleaseReturn = false) {
      const goHome = () => {
        this.$router.push('/')
      }
      if (document.referrer === '') {
        if (!pleaseReturn) {
          goHome()
        }
        return -1
      }
      const referrer = new URL(document.referrer)
      const now = new URL(window.location.href)
      if (!referrer) {
        if (!pleaseReturn) {
          goHome()
        }
        return 1
      }
      if (referrer.host !== now.host) {
        if (!pleaseReturn) {
          goHome()
        }
        return 2
      }
      let isRoot = Functions.isRoot(now.pathname)
      if (isRoot) {
        if (!pleaseReturn) {
          goHome()
        }
        return 3
      } else {
        if (referrer.host === now.host && !referrer.path) {
          this.$router.push('/')
        } else {
          router.back()
        }
        return 0
      }
    },
    changeTheme() {},
  },
}
</script>

<style lang="scss" scoped>
.nav {
  width: 100%;
  height: 2em;
  cursor: pointer;
  display: flex;
  align-items: center;
  margin-bottom: 0;
}
.text-none {
  font-weight: normal;
  font-size: 1.3em;
  padding-left: 0.5em;
  padding-right: 0.5em;
}
.ripple {
  &:hover {
    box-shadow:
      0px 2px 4px -1px var(--v-shadow-key-umbra-opacity, rgba(0, 0, 0, 0.2)),
      0px 4px 5px 0px var(--v-shadow-key-penumbra-opacity, rgba(0, 0, 0, 0.14)),
      0px 1px 10px 0px var(--v-shadow-key-penumbra-opacity, rgba(0, 0, 0, 0.12));
  }
}
button {
  margin: 0.2em !important;
}
.v-input {
  display: flex;
  padding-right: 10%;
  justify-content: flex-end;
}
.v-item {
  display: flex;
  align-items: center;
  .nav {
    margin-left: 16px;
  }
}
.inline {
  display: inline;
  vertical-align: middle;
}
.header-list {
  text-decoration: none;
  color: inherit;
}
</style>
