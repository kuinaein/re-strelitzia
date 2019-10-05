<template lang="pug">
include /components/mixins

.container
  div(v-if="null === accountSummary"): +loading
  template(v-else)
    .card(v-for="t of AccountTitleType"
        v-if="AccountTitleTypeDesc[t].statements[FinancialStatementType.BALANCE_SHEET]"
        :key="'summary-group-' + t" )
      .card-header(v-t="'enum.accountType.' + t")
      .card-body: table.table.table-striped: tbody
        tr(v-for="a of accountSummary[t]"
            :key="'summary-for-account-' + a.accountId")
          td: router-link.btn.btn-primary(
                :to="{name: 'ledger', params: {accountId: a.accountId, month: thisMonth}}")
            +faIcon("edit")
            | 元帳
          th {{ accountTitleMap[a.accountId].name }}
          td.text-right {{ formatCurrency(a.amount) }}
</template>

<script>
import moment from 'moment';
import axios from 'axios';

import {
  AccountTitleType,
  AccountTitleTypeDesc,
  FinancialStatementType,
} from '@/account/constants';

import { MOMENT_YEARMONTH_FORMAT } from '@/util/lang';
import { extendVue } from '@/core/vue';
import { mapConstants } from '@/util/vue-util';
import { AccountModule } from '@/account/AccountModule';

export default extendVue({
  data() {
    return {
      accountSummary: null,
    };
  },
  computed: {
    ...mapConstants({
      AccountTitleType,
      AccountTitleTypeDesc,
      FinancialStatementType,
    }),
    ...AccountModule.mapState([AccountModule.stateKey.accountTitleMap]),
    thisMonth() {
      return moment().format(MOMENT_YEARMONTH_FORMAT);
    },
  },
  methods: {
    streInit() {
      axios
        .post(this.apiRoot + '/journal/trial-balance', {
          accountTypes: [
            AccountTitleType.ASSET,
            AccountTitleType.LIABILITY,
            AccountTitleType.NET_ASSET,
          ],
        })
        .then(res => {
          const data = res.data.data;
          const accountSummary = {
            [AccountTitleType.ASSET]: [],
            [AccountTitleType.LIABILITY]: [],
            [AccountTitleType.NET_ASSET]: [],
          };
          for (const accountId in data) {
            accountSummary[this.accountTitleMap[accountId].type].push({
              accountId,
              amount: data[accountId],
            });
          }
          for (const s of Object.values(accountSummary)) {
            s.sort((a, b) => {
              return (
                this.accountTitleMap[a.accountId].order -
                this.accountTitleMap[b.accountId].order
              );
            });
          }
          this.accountSummary = accountSummary;
        })
        .catch(err => {
          alert('エラー: ' + err);
        });
    },
  },
});
</script>
