<template lang="pug">
include /components/mixins
- labelClass = 'col-form-label col-sm-3'
- controlClass = 'col-sm-9'

.container
  h1 資産・負債科目マスタ

  button.btn.btn-secondary(type="button" @click="create")
    +faIcon("plus")
    template 追加
  table.table.table-bordered.table-striped
    thead
      tr
        th 操作
        th タイプ
        th 名称
        th パス
    tbody
      tr(v-for="a of bsAccounts")
        td: button.btn.btn-primary(type="button" @click="edit(a.id)")
          +faIcon("edit")
          template 編集
        td(v-t="'enum.accountType.' + a.type" :class="accountTypeClass(a.type)")
        th: span(:style="'display:inline-block;padding-left: ' + (Math.max(0, a.level - 1) * 2) + 'ex'")
          template(v-if="0 !== a.level") |-
          template {{ a.name }}
        td {{ a.path }}
  modal(ref="editDlg")
    template(slot="title") 資産・負債科目の
      template(v-if="editing.bsAccount.id") 編集
      template(v-else) 追加
    template(v-if="null === editing.openingBalance"): +loading
    form(v-else)
      .form-group.row
        label(class=labelClass) 科目名
        div(class=controlClass)
          input.form-control(v-model="editing.bsAccount.name" autofocus required)
      .form-group.row
        label(class=labelClass) タイプ
        div(class=controlClass)
          input.form-control-plaintext(readonly v-if="editing.bsAccount.id"
              :value="$t('enum.accountType.' + editing.bsAccount.type)")
          select.form-control(v-else v-model="editing.bsAccount.type" required)
            option(v-for="t of targetTypes"
                :value="t" v-t="'enum.accountType.' + t"
                :key="'editing-account-type-choice-' + t")
      .form-group.row
        label(class=labelClass) 親科目
        div(class=controlClass)
          select.form-control(v-model="editing.bsAccount.parentId")
            option(:value="null") （なし）
            option(v-for="a in parentCandidates" :value="a.id"
                :key="'editing-account-parent-choice-' + a.id") {{ a.name }}
      .form-group.row
        label(class=labelClass) 開始残高
        div(class=controlClass)
          input.form-control(v-model="editing.openingBalance" type="number" min="0" required)
      .form-group.row: div.offset-sm-3(class=controlClass)
        button.btn.btn-primary(type="button" @click="doSave") 保存
        +modalCloseBtn("キャンセル")
</template>

<script>
import axios from 'axios';

import { csvContains } from '@/util/lang';
import { mapConstants } from '@/util/vue-util';

import { extendVue } from '@/core/vue';
import {
  ACCOUNT_PATH_SEPARATOR,
  AccountTitleType,
  AccountTitleTypeDesc,
  FinancialStatementType,
} from '@/account/constants';
import { AccountModule } from '@/account/AccountModule';

export default extendVue({
  data() {
    return {
      editing: {
        bsAccount: {
          name: '',
          type: AccountTitleType.ASSET,
          parentId: null,
        },
        openingBalance: 0,
      },
    };
  },
  computed: {
    ...mapConstants({ AccountTitleType }),
    ...AccountModule.mapState([
      AccountModule.stateKey.accountTitles,
      AccountModule.stateKey.accountTitleMap,
    ]),
    targetTypes() {
      return Object.keys(AccountTitleTypeDesc).reduce((r, k) => {
        if (
          AccountTitleTypeDesc[k].statements[
            FinancialStatementType.BALANCE_SHEET
          ]
        ) {
          r[k] = k;
        }
        return r;
      }, {});
    },
    bsAccounts() {
      return (this.accountTitles || []).filter(
        a =>
          AccountTitleTypeDesc[a.type].statements[
            FinancialStatementType.BALANCE_SHEET
          ]
      );
    },
    parentCandidates() {
      return this.bsAccounts.filter(
        a =>
          a.type === this.editing.bsAccount.type &&
          !csvContains(
            a.path,
            this.editing.bsAccount.path,
            ACCOUNT_PATH_SEPARATOR
          )
      );
    },
  },
  watch: {
    'editing.bsAccount.type'() {
      this.editing.bsAccount.parentId = null;
    },
  },
  methods: {
    ...AccountModule.mapActions([AccountModule.actionKey.LOAD_ALL]),
    accountTypeClass(type) {
      switch (type) {
        case AccountTitleType.ASSET:
        case AccountTitleType.NET_ASSET:
          return 'table-success';
        case AccountTitleType.LIABILITY:
          return 'table-danger';
      }
    },
    create() {
      this.editing = this.$options.data().editing;
      this.$refs.editDlg.open();
    },
    //     edit (id) {
    //       this.editing = {
    //         bsAccount: Object.assign({}, this.accountTitleMap[id]),
    //         openingBalance: null,
    //       };
    //       this.$refs.editDlg.open();
    //       axios.get(`${this.apiRoot}/journal/opening/${this.editing.bsAccount.id}`).then(res => {
    //         this.editing.openingBalance = res.data.data.amount;
    //       }).catch(() => {
    //         alert('開始残高を取得できません');
    //       });
    //     },
    doSave() {
      const promise = this.editing.bsAccount.id
        ? axios.put(
            `${this.apiRoot}/account/bs/${this.editing.bsAccount.id}`,
            this.editing
          )
        : axios.post(`${this.apiRoot}/account/bs`, this.editing);
      promise
        .then(() => {
          alert(`勘定科目「${this.editing.bsAccount.name}」を保存しました`);
          this[AccountModule.actionKey.LOAD_ALL]();
          this.$refs.editDlg.close();
        })
        .catch(err => {
          alert(
            `勘定科目「${
              this.editing.bsAccount.name
            }」の保存に失敗しました:  ${err}`
          );
        });
    },
  },
});
</script>
