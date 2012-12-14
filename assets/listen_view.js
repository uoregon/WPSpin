var Playlist = Playlist || {};

var View = Playlist.View; //require('./view');
var template = Playlist.Template;//require('./templates/listen');
var playlistTemplate = Playlist.PlaylistItem; //require('./templates/playlist_item');
var $ = jQuery;



Playlist.Listen = View.extend({
	el: "body",
	playlist_el: ".wps-playlist-items",
	playlist_template: _.template(playlistTemplate),
	
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
				$(_self.playlist_el).append(_self.playlist_template(item)).hide().fadeIn();
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

jQuery(document).ready(function () {
	new Playlist.Listen();
	Backbone.Mediator.publish("listen:load");
});
