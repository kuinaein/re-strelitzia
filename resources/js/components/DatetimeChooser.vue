<template lang="pug">
input(:type="type" :value="valueForElement" @input="handleInput")
</template>

<script>
import Vue from 'vue';
import moment from 'moment';
import { MOMENT_DATETIME_FORMAT } from '@/util/lang';

export default {
  props: ['value', 'type'],
  computed: {
    valueForElement() {
      return Vue.filter(this.type)(this.value);
    },
  },
  methods: {
    handleInput(ev, o) {
      const el = ev.target;
      this.$emit('input', moment(el.value).format(MOMENT_DATETIME_FORMAT));
    },
  },
};
</script>
