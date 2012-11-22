var Playlist = Playlist || {};

var Collection = Playlist.Collection; //require('./collection');
var SongModel = Playlist.SongModel; //require('./song_model');

Playlist.PlaylistCollection = Collection.extend({
	model: SongModel,
	initialize: function () {
		_.bindAll(this, "_loadCollection", "loadData", "_loadYQL");
	},
	
	loadData: function () {
		return this._loadCollection(this._loadYQL());
	},
	
	startTimer: function() {
		this._timer();
	},
	
	_loadYQL: function () {
		var url = "http://query.yahooapis.com/v1/public/yql?q=select%20*%20from%20xml%20where%20url=%22http://spinitron.com/radio/rss.php?station=kwva%22&format=json";
		var xmlPromise = $.getJSON(url);
		xmlPromise = xmlPromise.pipe(function (data) {
			return data.query.results.rss.channel.item;
		});
		return xmlPromise; 
	},
	
	_loadCollection: function (dataPromise) {
		var _self = this;
		var promise = dataPromise.pipe(function(data) {
			if (_self.length <= 0) {
				_self._initLoad(data);
			} else {
				_self._updateLoad(data);
			}
			return _self;
		});
		return promise;
	},
	
	_initLoad: function (data) {
		var _self = this;
		data = _.first(data, 10);
		_self.reset(data);
	},
	
	_updateLoad: function (data) {
		data = _.first(data, 10);
		
		var _self = this;
		var timestamp = _self.first().get("pubDate");
		_self.reset(data);
		
		var new_items = [];
		_.each(data, function(item) {
			if(Date.parse(timestamp) < Date.parse(item.pubDate)) {
				new_items.push(item);
			} else {
			}
		});
		Backbone.Mediator.pub("listen:playlist:update", new_items);
	},
	
	_timer: function () {
		var _self = this;
		setInterval(function() {
			_self._loadCollection(_self._loadYQL());
		}, 30000);
	}
	
});
