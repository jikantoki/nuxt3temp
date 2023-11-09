<template lang="pug">
.about
  h1 This is an about page
  adsense
</template>

<script>
import metaFunctions from '~/js/metaFunctions'
import mixins from '~/mixins/mixins'

export default {
  mixins: [mixins],
  mounted() {
    this.setTitle('About')
  },
}
</script>
