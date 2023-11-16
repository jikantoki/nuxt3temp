<template lang="pug">
.index-page
  .wrap
    v-card.content(elevation="4")
      .text-h2 利用規約
      hr
      .text 誹謗中傷の禁止
      p 良くないと思う
</template>

<script>
/**
 * ページ推移ごとにmountedを実行する必要があるため、どのviewsでも読み込むこと
 */
import mixins from '~/mixins/mixins'
import webpush from '~/js/webpush'
import metaFunctions from '~/js/metaFunctions'
export default {
  name: 'index',
  components: {},
  mixins: [mixins],
  data() {
    return {}
  },
  mounted() {
    this.setTitle('利用規約')
  },
  methods: {},
}
</script>

<style lang="scss" scoped></style>
