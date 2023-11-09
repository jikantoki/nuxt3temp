<template lang="pug">
.login(v-if="isShow")
  v-form.center
    img.ma-8(src="~/assets/logo.png")
    p.form-p.text-h6 ログインして、世界とつながろう
    v-container
      p.error.pa-4.mb-4.relative(v-if="errorMessage")
        v-icon mdi-alert-circle-outline
        p.px-4 {{ errorMessage }}
        v-icon.v-ripple.absolute.close-error(
          v-ripple
          @click="errorMessage=false"
          ) mdi-close-circle-outline
      v-text-field(
        v-model="userName"
        label="ID"
        prepend-inner-icon="mdi-account-outline"
        required
        clearable
        ref="userName"
        @keydown.enter="$refs.password.focus()"
        )
      v-text-field(
        v-model="password"
        label="Password"
        prepend-inner-icon="mdi-lock-outline"
        :type="showPassword ? 'text' : 'password'"
        :append-inner-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
        @click:append-inner="showPassword = !showPassword"
        required
        ref="password"
        @keydown.enter="login()"
        )
      .btns
        v-btn.round.submit(
          @click="login"
          :disabled="!userName || !password"
          :loading="loading"
          ref="submit"
          ) Login
        v-btn.round(@click="a('/registar')" v-show="!loading") Registar Account
</template>

<script>
import metaFunctions from '~/js/metaFunctions'
import mixins from '~/mixins/mixins'
export default {
  mixins: [mixins],
  data() {
    return {
      /** 将来的にv-dialogとかでフォームを埋め込む用 */
      isShow: true,
      userName: '',
      password: '',
      showPassword: false,
      loading: false,
      errorMessage: null,
      userStore: useUserStore(),
    }
  },
  mounted() {
    this.setTitle('ログイン')
    if (localStorage.userIdForLogin) {
      this.userName = localStorage.userIdForLogin
    }
  },
  unmounted() {
    if (this.userName) {
      localStorage.userIdForLogin = this.userName
    } else {
      localStorage.userIdForLogin = ''
    }
  },
  methods: {
    async login() {
      this.loading = true
      this.sendAjaxWithAuth('/loginAccount.php', {
        id: this.userName,
        password: this.password,
      })
        .then(async (e) => {
          console.log(e)
          this.loading = false
          if (e.body.status === 'ok') {
            const now = new URL(window.location.href)
            this.userStore.setId(e.body.id)
            this.userStore.setToken(e.body.token)
            const profile = await this.getProfile(e.body.id)
            this.userStore.setProfile(profile)
            const redirect = now.searchParams.get('redirect')
            if (redirect && redirect !== '') {
              this.a(redirect)
            } else {
              this.a('/')
            }
          } else {
            this.errorMessage = 'ユーザー名またはパスワードが間違っています'
          }
        })
        .catch((e) => {
          console.log(e)
          this.loading = false
        })
    },
  },
}
</script>

<style lang="scss" scoped>
.login {
  position: relative;
  display: contents;
  .center {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    display: flex;
    flex-direction: column;
    max-width: 32em;
  }
}
.btns {
  display: flex;
  flex-direction: row-reverse;
  .round {
    border-radius: 9999px;
  }
  .submit {
    background-color: var(--accent-color);
    color: var(--accent-text-color);
  }
}
img {
  height: 8em;
  object-fit: contain;
}
.form-p {
  text-align: center;
}
.v-btn:disabled {
  opacity: 0.7;
}
.error {
  background-color: var(--color-error);
  color: white;
  display: flex;
  border-radius: 4px;
}
.v-ripple {
  border-radius: 9999px;
  cursor: pointer;
}
.close-error {
  right: 16px;
}
</style>
