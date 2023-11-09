import { ref, computed } from 'vue'
import { defineStore } from 'pinia'

export const useUserStore = defineStore('userStore', () => {
  const userId = ref(null)
  const userToken = ref(null)
  function setId(newId) {
    userId.value = newId
  }
  function setToken(newToken) {
    userToken.value = newToken
  }
  return {
    userId,
    userToken,
    setId,
    setToken,
  }
})
