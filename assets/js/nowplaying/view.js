var NowPlaying = NowPlaying || {};
// Base class for all views.

NowPlaying.View = (function ($, Backbone) {
  return Backbone.View.extend({
    initialize: function() {
      this.render = _.bind(this.render, this);
    },

    template: function() {},
    getRenderData: function() {},

    rendered: false,

    render: function() {
      this.beforeRender();
      if (this.rendered) return;
      this.$el.html(this.template(this.getRenderData()));
      this.afterRender();
      this.rendered = true;
      return this;
    },

    beforeRender: function() {},

    afterRender: function() {}
  });
})(jQuery, Backbone);
