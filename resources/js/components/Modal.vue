<template lang="pug">
.modal.fade(tabindex="-1"): .modal-dialog: .modal-content
  .modal-header
    h5.modal-title: slot(name="title")
    button.close(type="button" data-dismiss="modal") x
  .modal-body
    slot
</template>

<style scoped>
.modal-body {
  overflow-y: auto;
  max-height: 80vh;
}
</style>

<script>
export default {
  data () {
    return {
      onShownHandler: null,
    };
  },
  mounted () {
    this.onShownHandler = () => {
      const el = this.$el.querySelector('[autofocus]');
      el && el.focus();
    };
    jQuery(this.$el).on('shown.bs.modal', this.onShownHandler);
  },
  beforeDestroy () {
    jQuery(this.$el).off('shown.bs.modal', this.onShownHandler);
  },
  methods: {
    open () {
      jQuery(this.$el).modal('show');
    },
    close () {
      jQuery(this.$el).modal('hide');
    },
  },
};
</script>
