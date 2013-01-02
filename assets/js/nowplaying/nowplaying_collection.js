var NowPlaying = NowPlaying || {};

NowPlaying.ShowCollection= (function ($, Collection, ShowModel) {
  return Collection.extend({
    model: ShowModel,
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
      var xmlPromise = $.post(url, {action: 'get-current-show'});
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
      var title = _self.first().get("title");
      _self.reset(data);
      var new_title = _self.first().get("title");
      if (title != new_title) {
        Backbone.Mediator.pub("nowplaying:update");
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

})(jQuery, NowPlaying.Collection, NowPlaying.ShowModel);
