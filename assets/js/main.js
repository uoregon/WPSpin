jQuery(document).ready(function () {
  new Playlist.Listen();
  new NowPlaying.Display();
  Backbone.Mediator.publish("listen:load");
  Backbone.Mediator.publish("nowplaying:load");
});
