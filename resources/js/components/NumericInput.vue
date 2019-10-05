<template lang="pug">
input(type="number" :value="value" @input="handleInput")
</template>

<script>
export default {
  props: {
    value: Number,
  },
  methods: {
    handleInput(ev, o) {
      this.$emit('input', parseInt(ev.target.value));
    },
  },
};
</script>
