<template lang="pug">
input(:type="type" :value="valueForElement" @input="handleInput")
</template>

<script>
import Vue from 'vue';
import moment from 'moment';
import { MOMENT_DATETIME_FORMAT } from '@/util/lang';

export default {
  props: {
    value: String,
    type: { validator: p => 'date' === p },
  },
  computed: {
    valueForElement() {
      return Vue.filter(this.type)(this.value);
    },
  },
  methods: {
    handleInput(ev, o) {
      this.$emit(
        'input',
        moment(ev.target.value).format(MOMENT_DATETIME_FORMAT)
      );
    },
  },
};
</script>
