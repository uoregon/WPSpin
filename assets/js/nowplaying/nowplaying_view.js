var NowPlaying = NowPlaying || {};

NowPlaying.Display = (function ($, View, NowPlaying) {
  return View.extend({
    el: "body",
    nowplaying_el: ".wps-now-playing",
    nowplaying_template: function () {
      return _.template($(".wps-nowplaying-template").html());
    },
    initialize: function () {
      _.bindAll(this, "render");
      Backbone.Mediator.sub("nowplaying:load", this.render, this);
      Backbone.Mediator.sub("nowplaying:update", this.updateNowPlaying, this);
      var nowplaying = new NowPlaying.ShowCollection();
      this.collectionPromise = nowplaying.loadData();
      nowplaying.startTimer();
    },

    render: function() {
      this.renderNowPlaying();
    },

    renderNowPlaying: function() {
      var _self = this;
      $(_self.nowplaying_el).html('');
      this.collectionPromise.pipe(function (collection) {
        collection.each(function (item) {
          item = _self.formatItem(item.toJSON());
          var template = _self.nowplaying_template();
          $(_self.nowplaying_el).html(template(item)).hide().fadeIn();
        });
      });
    },

    updateNowPlaying: function(data) {
      var _self = this;
      _self.renderNowPlaying();
    },

    formatItem: function(item) {
      var _self = this;
      return {
        title: item.title,
        description: item.description,
        image: item.image,
        profiles: item.DJProfiles,
      };

    }

  });
})(jQuery, NowPlaying.View, NowPlaying);
