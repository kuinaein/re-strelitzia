import { mapState } from 'vuex';

const stateKey = {
  apiRoot: 'apiRoot',
};

const vuexModule = {
  state: {
    [stateKey.apiRoot]: '/api',
  },
};

export const CoreModule = {
  stateKey,
  mapState: (arg) => mapState(arg),
  vuexModule,
};
