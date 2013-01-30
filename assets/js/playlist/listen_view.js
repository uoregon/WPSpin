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

    formatAMPM: function(date) {
      date = date.split(':');
      var hours = date[0];
      var minutes = date[1];
      var ampm = hours >= 12 ? 'pm' : 'am';
      hours = hours % 12;
      hours = hours ? hours : 12; // the hour '0' should be '12'
      var strTime = hours + ':' + minutes + ' ' + ampm;
      return strTime;
    },

    formatItem: function(item) {
      var _self = this;
      return {
        artist: item.ArtistName,
        track: item.SongName,
        timestamp: _self.formatAMPM(item.Timestamp),
        pubDate: item.DiskReleased,
      };

    }

  });
})(jQuery, Playlist.View);





