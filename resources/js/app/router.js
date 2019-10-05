import Vue from 'vue';
import VueRouter from 'vue-router';

import SummaryPage from '@/app/SummaryPage';
import BsAccountListPage from '@/account/BsAccountListPage';
import PlAccountListPage from '@/account/PlAccountListPage';
import JournalSchedulePage from '@/journal/JournalSchedulePage';
import LedgerPage from '@/journal/LedgerPage';

Vue.use(VueRouter);

const routes = [
  {
    path: '/',
    name: 'summary',
    component: SummaryPage,
  },
  {
    path: '/account/bs',
    name: 'bs-account-list',
    component: BsAccountListPage,
  },
  {
    path: '/account/pl',
    name: 'pl-account-list',
    component: PlAccountListPage,
  },
  {
    path: '/journal/schedule',
    name: 'journal-schedule',
    component: JournalSchedulePage,
  },
  {
    path: '/journal/ledger/:accountId/:month',
    name: 'ledger',
    component: LedgerPage,
  },
].map(v => {
  v.props = true;
  return v;
});

export const router = new VueRouter({
  mode: 'history',
  routes,
});
