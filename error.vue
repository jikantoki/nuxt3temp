<template lang="pug">
.error-page
  .error {{ error }}
</template>

<script>
export default {
  layout: 'default',
  head() {
    return {
      title: 'ふえ～',
    }
  },
  data() {
    return {
      error: useError(),
    }
  },
}
</script>
