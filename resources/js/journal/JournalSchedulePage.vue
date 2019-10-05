<template lang="pug">
include /components/mixins
- labelClass = 'col-form-label col-sm-4'
- controlClass = 'col-sm-8'

.container
  h1 定期仕訳
  template(v-if="null === schedules"): +loading
  template(v-else)
    .alert.alert-info(v-if="0 < overdues.length"): ul
      li(v-for="o of overdues" :key="'overdue-schedule-' + o.id")
        | 「{{o.nextPostDate}}」に「{{o.remarks}}」の定期仕訳が組まれています
    p
      button.btn.btn-primary(type="button" @click="doJournalAll" :disabled="0 === overdues.length")
        +faIcon("flash")
        | 一括仕訳
      button.btn.btn-secondary(type="button" @click="createSchedule")
        +faIcon("plus")
        | 追加
    table.table.table-bordered.table-striped
      thead: tr
        th 操作
        th 摘要
        th 有効
        th スケジュール
        th 次の仕訳日
        th 借方科目
        th 貸方科目
        th 金額
      tbody: tr(v-for="s of schedules" :key="'journal-schedule-' + s.id")
        td
          button.btn.btn-primary(type="button" @click="doJournal(s)" :disabled="!s.enabled || now.isBefore(s.nextPostDate)")
            +faIcon("flash")
            | 仕訳
          button.btn.btn-secondary(type="button" @click="editSchedule(s)")
            +faIcon("edit")
            | 編集
        th {{ s.remarks }}
        td(v-if="s.enabled") 有効
        td(v-else)
        td 毎月 {{ s.postDate }} 日
        td {{ s.nextPostDate | date }}
        td {{ accountTitleMap[s.debitAccountId].name }}
        td {{ accountTitleMap[s.creditAccountId].name }}
        td {{ formatCurrency(s.amount) }}
    modal(ref="editDlg")
      template(slot="title") 定期仕訳の
        template(v-if="editing.id") 編集
        template(v-else) 追加
      .alert.alert-info: ul
        li <strong>支出</strong>の場合: <strong>借方に費用科目</strong>を選択してください。
        li <strong>収入</strong>の場合: <strong>貸方に収益科目</strong>を選択してください。
        li 相手科目に資産・負債科目を選択してください。
        li <strong>口座間移動</strong>の場合: <strong>借方に移動先の資産科目</strong>を選択してください。
      form
        .form-group.row
          div.offset-sm-4(class=controlClass): label
            input(type="checkbox" v-model="editing.enabled")
            | 有効
        .form-group.row
          label(class=labelClass) 摘要
          div(class=controlClass)
            input.form-control(v-model="editing.remarks" autofocus)
        .form-group.row
          label(class=labelClass) スケジュール
          div.form-control-static(class=controlClass)
            | 毎月&nbsp;
            numeric-input(v-model="editing.postDate" min="1" max="28" required)
            | &nbsp;日に実行
        .form-group.row
          label(class=labelClass) 次の仕訳日
          div(class=controlClass)
            datetime-chooser.form-control(v-model="editing.nextPostDate" type="date" required)
        .form-group.row
          label(class=labelClass) 借方科目
          div(class=controlClass)
            account-chooser.form-control(v-model="editing.debitAccountId" required)
        .form-group.row
          label(class=labelClass) 貸方科目
          div(class=controlClass)
            account-chooser.form-control(v-model="editing.creditAccountId" required)
        .form-group.row
          label(class=labelClass) 金額
          div(class=controlClass)
            numeric-input.form-control(v-model="editing.amount" min="1" required)
        .form-group.row: div.offset-sm-3(class=controlClass)
          button.btn.btn-primary(type="button" @click="doSave") 保存
          +modalCloseBtn("キャンセル")
</template>

<script>
import axios from 'axios';
import moment from 'moment';

import { MOMENT_ISO_DATE_FORMAT, MOMENT_DATETIME_FORMAT } from '@/util/lang';
import { extendVue } from '@/core/vue';
import { AccountModule } from '@/account/AccountModule';

export default extendVue({
  data() {
    return {
      schedules: null,
      editing: {
        enabled: true,
        postDate: 1,
        nextPostDate: moment()
          .add(1, 'month')
          .date(1)
          .format(MOMENT_DATETIME_FORMAT),
        remarks: '',
        debitAccountId: null,
        creditAccountId: null,
        amount: 0,
      },
    };
  },
  computed: {
    ...AccountModule.mapState([
      AccountModule.stateKey.accountTitles,
      AccountModule.stateKey.accountTitleMap,
    ]),
    now() {
      return moment();
    },
    overdues() {
      if (!this.schedules) {
        return [];
      }
      const now = moment();
      return this.schedules.filter(
        s => s.enabled && now.isAfter(s.nextPostDate)
      );
    },
  },
  watch: {
    'editing.postDate'() {
      if (this.editing.id) {
        return;
      }
      const next = moment().date(this.editing.postDate);
      if (next.isBefore(moment())) {
        next.add(1, 'month');
      }
      this.editing.nextPostDate = next.format(MOMENT_DATETIME_FORMAT);
    },
  },
  methods: {
    streInit() {
      axios
        .get(`${this.apiRoot}/journal/schedule`)
        .then(res => {
          const schedules = res.data.data;
          schedules.sort((a, b) => {
            if (a.enabled !== b.enabled) {
              return a.enabled ? -1 : 1;
            }
            if (a.nextPostDate !== b.nextPostDate) {
              return a.nextPostDate < b.nextPostDate ? -1 : 1;
            }
            return a.id === b.id ? 0 : a.id < b.id ? -1 : 1;
          });
          this.schedules = schedules;
        })
        .catch(err => {
          alert('定期仕訳一覧の取得に失敗しました： ' + err);
        });
    },
    createSchedule() {
      this.editing = this.$options.data().editing;
      this.editing.debitAccountId = this[
        AccountModule.stateKey.accountTitles
      ][0].id;
      this.editing.creditAccountId = this[
        AccountModule.stateKey.accountTitles
      ][1].id;
      this.$refs.editDlg.open();
    },
    editSchedule(schedule) {
      this.editing = Object.assign({}, schedule);
      this.$refs.editDlg.open();
    },
    doSave() {
      const promise = this.editing.id
        ? axios.put(
            `${this.apiRoot}/journal/schedule/${this.editing.id}`,
            this.editing
          )
        : axios.post(`${this.apiRoot}/journal/schedule`, this.editing);
      promise
        .then(() => {
          alert('保存しました');
          this.$refs.editDlg.close();
          this.streInit();
        })
        .catch(err => {
          alert('保存に失敗しました: ' + err);
        });
    },
    doJournalAll() {
      if (!confirm('仕訳日を過ぎた項目の一括仕訳を行います')) {
        return;
      }
      this.doJournalImpl(this.overdues)
        .then(savedIds => {
          alert(savedIds.join('番、') + '番として記帳しました');
          this.streInit();
        })
        .catch(err => {
          alert('一括仕訳に失敗しました: ' + err);
        });
    },
    doJournal(schedule) {
      if (
        !confirm(
          `「${schedule.remarks}」を「${
            schedule.nextPostDate
          }」付で記帳してよろしいですか？`
        )
      ) {
        return;
      }
      this.doJournalImpl([schedule])
        .then(savedIds => {
          alert(savedIds[0] + '番として記帳しました');
          this.streInit();
        })
        .catch(err => {
          alert('保存に失敗しました: ' + err);
        });
    },
    /**
     * @param {Object[]} schedules
     * @returns {Promise<Number[]>}
     */
    doJournalImpl(schedules) {
      const savedIds = [];
      let promise = Promise.resolve();
      for (const schedule of schedules) {
        const postData = {
          journalDate: schedule.nextPostDate,
          remarks: schedule.remarks,
          debitAccountId: schedule.debitAccountId,
          creditAccountId: schedule.creditAccountId,
          amount: schedule.amount,
        };
        // TODO 本来はサーバ側でトランザクションをかけてやるべきでは
        promise = promise
          .then(() => {
            return axios.post(`${this.apiRoot}/journal`, postData);
          })
          .then(res => {
            savedIds.push(res.data.data.id);
            // 「次の仕訳日」の更新
            const newSchedule = Object.assign({}, schedule);
            const prevPostDate = moment(schedule.nextPostDate);
            if (10 > prevPostDate.date() && 20 < schedule.postDate) {
              // 月末払いのものを翌月頭に先送りしたケースとする
              newSchedule.nextPostDate = prevPostDate
                .date(schedule.postDate)
                .format(MOMENT_ISO_DATE_FORMAT);
            } else if (20 < prevPostDate.date() && 10 > schedule.postDate) {
              // 月初払いのものを前月末に前倒ししたケースとする
              newSchedule.nextPostDate = prevPostDate
                .add(2, 'months')
                .date(schedule.postDate)
                .format(MOMENT_DATETIME_FORMAT);
            } else {
              newSchedule.nextPostDate = prevPostDate
                .add(1, 'month')
                .date(schedule.postDate)
                .format(MOMENT_DATETIME_FORMAT);
            }
            return axios.put(
              `${this.apiRoot}/journal/schedule/${newSchedule.id}`,
              newSchedule
            );
          });
      }
      return promise.then(() => savedIds);
    },
  },
});
</script>
