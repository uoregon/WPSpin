var Playlist = Playlist || {};

var Collection = Playlist.Collection; //require('./collection');
var SongModel = Playlist.SongModel; //require('./song_model');
var $ = jQuery;

Playlist.PlaylistCollection = Collection.extend({
	model: SongModel,
	initialize: function () {
		_.bindAll(this, "_loadCollection", "loadData", "_loadAjax");
	},
	
	loadData: function () {
		return this._loadCollection(this._loadAjax());
	},
	
	startTimer: function() {
		this._timer();
	},
	
	_loadAjax: function () {
		var url = WPSpinAjax.url;
		var xmlPromise = $.post(url, {action: 'retrieve-playlist'});
		xmlPromise = xmlPromise.pipe(function (data) {
			return JSON.parse(data);
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
		_self.reset(data);
	},
	
	_updateLoad: function (data) {
		var _self = this;
    var timestamp = _self.first().get("Date") + " " + _self.first().get("Timestamp");
    _self.reset(data);
    var new_timestamp = _self.first().get("Date") + " " + _self.first().get("Timestamp");
    if(Date.parse(timestamp) < Date.parse(new_timestamp)) {
      Backbone.Mediator.pub("listen:playlist:update");
      return;
    }
  },

  _timer: function () {
    var _self = this;
    setInterval(function() {
      _self._loadCollection(_self._loadAjax());
    }, 30000);
  }

});
