<template lang="pug">
include /components/mixins

div
  .navbar.navbar-expand-sm.navbar-light.bg-light
    router-link.navbar-brand(to="/") Re:すとれりちあ
    button.navbar-toggler(type="button" aria-label="メニュー開閉" data-toggle="collapse"
        data-target="#stre-navbar" area-controls="stre-navbar" aria-expanded="false")
      span.navbar-toggler-icon
    #stre-navbar.collapse.navbar-collapse: ul.navbar-nav.mr-auto
      //- li.nav-item: router-link.nav-link(:to="{name: 'journal-schedule'}") 定期仕訳
      li.nav-item: router-link.nav-link(:to="{name: 'bs-account-list'}") 資産・負債科目
      //- li.nav-item: router-link.nav-link(:to="{name: 'pl-account-list'}") 収益・費用科目
  .container(v-if="!accountTitles"): +loading
  router-view(v-else)
</template>

<script>
import { extendVue } from '@/core/vue';
import { AccountModule } from '@/account/AccountModule';

export default extendVue({
  computed: AccountModule.mapState([AccountModule.stateKey.accountTitles]),
  methods: AccountModule.mapActions({
    loadAllAccountData: AccountModule.actionKey.LOAD_ALL,
    streInit() {
      this.loadAllAccountData().catch(err => {
        alert('エラー！: ' + err);
      });
    },
  }),
});
</script>
