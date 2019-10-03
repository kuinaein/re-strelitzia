import Vue from 'vue';
import Vuex from 'vuex';

import { CoreModule } from '@/core/CoreModule';
import { AccountModule } from '@/account/AccountModule';

Vue.use(Vuex);

export const store = new Vuex.Store(Object.assign({}, CoreModule.vuexModule, {
  modules: {
    [AccountModule.namespace]: AccountModule.vuexModule,
  },
}));
