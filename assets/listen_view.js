var Playlist = Playlist || {};

var View = Playlist.View; //require('./view');
var template = Playlist.Template;//require('./templates/listen');
var Playlist = Playlist.PlaylistCollection; //require('../models/playlist_collection');
var playlistTemplate = Playlist.PlaylistItem; //require('./templates/playlist_item');



Playlist.Listen = View.extend({
	el: "body",
	playlist_el: ".playlist-items",
	playlist_template: playlistTemplate,
	
	initialize: function () {
		_.bindAll(this, "render");
		Backbone.Mediator.sub("listen:load", this.render, this);
		Backbone.Mediator.sub("listen:playlist:update", this.updatePlaylist, this);
		var playlist = new Playlist();
		this.collectionPromise = playlist.loadData();
		playlist.startTimer();
	},
	
	render: function() {
		this.renderPlaylist();
	},
	
	renderPlaylist: function() {
		var _self = this;
		this.collectionPromise.pipe(function (collection) {
			collection.each(function (item) {
				item = _self.formatItem(item.toJSON());
				console.log(item);
				$(_self.playlist_el).append(_self.playlist_template(item));
			});
		});
	},
	
	updatePlaylist: function(data) {
		var _self = this;
		_.each(data, function (item) {
			item = _self.formatItem(item);
			$(_self.playlist_el).prepend(_self.playlist_template(item)).hide().fadeIn();
		});
		
	},
	
	formatItem: function(item) {
		var _self = this;
		var split_items = item.title.split(": ");
		return {
			artist: split_items[0],
			track: split_items[1],
			pubDate: item.pubDate,
			link: item.link
		};
		
	}
	
});
