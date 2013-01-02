var Playlist = Playlist || {};

Playlist.Listen = (function ($, View) {
  return View.extend({
    el: "body",
    playlist_el: ".wps-playlist-items",
    playlist_template: function () {
      return _.template($(".wps-playlist-template").html());
    },
    initialize: function () {
      _.bindAll(this, "render");
      Backbone.Mediator.sub("listen:load", this.render, this);
      Backbone.Mediator.sub("listen:playlist:update", this.updatePlaylist, this);
      var playlist = new Playlist.PlaylistCollection();
      this.collectionPromise = playlist.loadData();
      playlist.startTimer();
    },

    render: function() {
      this.renderPlaylist();
    },

    renderPlaylist: function() {
      var _self = this;
      $(_self.playlist_el).html('');
      this.collectionPromise.pipe(function (collection) {
        collection.each(function (item) {
          item = _self.formatItem(item.toJSON());
          var template = _self.playlist_template();
          $(_self.playlist_el).append(template(item)).hide().fadeIn();
        });
      });
    },

    updatePlaylist: function(data) {
      var _self = this;
      _self.renderPlaylist();
    },

    formatItem: function(item) {
      var _self = this;
      return {
        artist: item.ArtistName,
        track: item.SongName,
        pubDate: item.DiskReleased,
      };

    }

  });
})(jQuery, Playlist.View);





