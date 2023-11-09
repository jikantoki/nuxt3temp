<template lang="pug">
.about
  h1 This is an about page
  adsense
</template>

<script>
import mixins from '~/mixins/mixins'

export default {
  mixins: [mixins],
  mounted() {
    this.metaStore.setTitle('About')
  },
}
</script>
