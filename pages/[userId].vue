<template lang="pug">
p Hello! {{ $route.params }}
</template>

<script>
import metaFunctions from '~/js/metaFunctions'
import mixins from '~/mixins/mixins'
export default {
  mixins: [mixins],
  mounted() {
    this.setTitle($route.params)
  },
}
</script>
