import { createNamespacedHelpers } from 'vuex';
import axios from 'axios';

import { ACCOUNT_PATH_SEPARATOR, AccountTitleTypeDesc } from '@/account/constants';

const stateKey = {
  accountTitles: 'accountTitles',
  accountTitleMap: 'accountTitleMap',
};

const mutaionKey = {
  CACHE_ACCOUNT_TITLES: 'CACHE_ACCOUNT_TITLES',
};

const actionKey = {
  LOAD_ALL: 'LOAD_ALL',
};

const vuexModule = {
  namespaced: true,
  state: {
    [stateKey.accountTitles]: null,
    [stateKey.accountTitleMap]: null,
  },
  mutations: {
    [mutaionKey.CACHE_ACCOUNT_TITLES] (state, {accountTitles, accountTitleMap}) {
      state[stateKey.accountTitles] = accountTitles;
      state[stateKey.accountTitleMap] = accountTitleMap;
    },
  },
  actions: {
    [actionKey.LOAD_ALL] ({commit, rootState}) {
      commit(mutaionKey.CACHE_ACCOUNT_TITLES, {});
      return axios.get(rootState.apiRoot + '/account').then(res => {
        const accountTitles = res.data.data;
        const accountTitleMap = {};
        // order, path, level, children はフロントエンド側で保管
        for (const a of accountTitles) {
          accountTitleMap[a.id] = a;
          a.children = [];
        }
        for (const a of accountTitles) {
          if (a.parentId) {
            accountTitleMap[a.parentId].children.push(a);
          }
          let path = a.name;
          let level = 0;
          let b = a;
          while (b.parentId) {
            ++level;
            b = accountTitleMap[a.parentId];
            if (b.path) {
              path = b.path + ACCOUNT_PATH_SEPARATOR + path;
              level += b.level;
              break;
            } else {
              path = b.name + ACCOUNT_PATH_SEPARATOR + path;
            }
          }
          a.path = path;
          a.level = level;
        }

        accountTitles.sort((o1, o2) => {
          if (o1.type !== o2.type) {
            return AccountTitleTypeDesc[o1.type].order -
                AccountTitleTypeDesc[o2.type].order;
          }
          return o1.path === o2.path ? 0 : o1.path < o2.path ? -1 : 1;
        });
        for (let i = 0; i < accountTitles.length; ++i) {
          accountTitles[i].order = i;
        }
        commit(mutaionKey.CACHE_ACCOUNT_TITLES, {accountTitles, accountTitleMap});
      });
    },
  },
};

export const AccountModule = {
  namespace: 'account',
  stateKey,
  mutaionKey,
  actionKey,
  vuexModule,
};

Object.assign(AccountModule, createNamespacedHelpers(AccountModule.namespace));
