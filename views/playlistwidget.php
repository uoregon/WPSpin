<?php
/**
 * Playlist Widget Template
 */
?>
<section class="wps-playlist-items"></section>
<script type="text/javascript">
jQuery(document).ready(function () {
  new Playlist.Listen();
  Backbone.Mediator.publish("listen:load");
});
</script>
<script type="text/template" class="wps-playlist-template">
	<div class="wps-playlist-item">
        <p class="track"><%= track %> <span class="timestamp"><%= timestamp %></span></p>
        <p class="artist"><%= artist %></p>
    </div>
</script>
