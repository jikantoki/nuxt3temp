<template lang="pug">
.error-page
  .error {{ error }}
</template>

<script>
export default {
  data() {
    return {
      error: useError(),
    }
  },
}
</script>
