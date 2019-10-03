import { CoreModule } from '@/core/CoreModule';

const BaseVue = {
  computed: CoreModule.mapState([CoreModule.stateKey.apiRoot]),
  mounted () {
    this.streInit && this.streInit();
  },
  watch: {
    $route (to, from) {
      for (const routeRecord of to.matched) {
        for (const inst of Object.values(routeRecord.instances)) {
          if (this === inst) {
            this.streInit && this.streInit();
            return;
          }
        }
      }
    },
  },
  methods: {
    formatCurrency (n) {
      return !n ? ''
        : n.toLocaleString('ja-JP', {style: 'currency', currency: 'JPY'});
    },
  },
};

export function extendVue(options) {
  const v = Object.assign({}, BaseVue, options);
  v.computed = Object.assign({}, BaseVue.computed, options.computed || {});
  v.watch = Object.assign({}, BaseVue.watch, options.watch || {});
  v.methods = Object.assign({}, BaseVue.methods, options.methods || {});
  return v;
}
