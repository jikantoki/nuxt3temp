<template lang="pug">
.login(v-if="isShow")
  v-form.center
    img.ma-8(src="~/assets/logo.png")
    p.form-p.text-h6 ログインして、世界とつながろう
    v-container
      v-text-field(v-model="userName" label="ID" prepend-inner-icon="mdi-account-outline" required clearable)
      v-text-field(v-model="password" label="Password" prepend-inner-icon="mdi-lock-outline" :type="showPassword ? 'text' : 'password'" :append-inner-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'" @click:append-inner="showPassword = !showPassword" required)
      .btns
        v-btn.round.submit(@click="login" :disabled="!userName || !password") Login
        v-btn.round(@click="a('/registar')") Registar Account
</template>

<script>
import mixins from '~/mixins/mixins'
export default {
  mixins: [mixins],
  data() {
    return {
      /** 将来的にdivの一つとしてフォームを埋め込む用 */
      isShow: true,
      userName: '',
      password: '',
      showPassword: false,
    }
  },
  mounted() {
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
    login() {},
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
</style>
