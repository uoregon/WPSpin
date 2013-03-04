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
        <p class="track"><%= track %></p>
        <p class="artist"><%= artist %></p>
        <p class="timestamp"><%= timestamp %></p>
</script>
