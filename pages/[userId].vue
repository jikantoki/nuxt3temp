<template lang="pug">
p Hello! {{ $route.params }}
</template>

<script>
import mixins from '~/mixins/mixins'
export default {
  mixins: [mixins],
  mounted() {
    this.metaStore.setTitle($route.params)
  },
}
</script>
