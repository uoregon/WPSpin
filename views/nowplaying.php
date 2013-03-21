<?php
/**
 * Now Playing Template
 */
?>
<div class="wps-now-playing"></div>
<script type="text/javascript">
jQuery(document).ready(function () {
  new NowPlaying.Display();
  Backbone.Mediator.publish("nowplaying:load");
});
</script>

<script type="text/template" class="wps-nowplaying-template">
<% if (image != false) { %>
  <img src="<%= image %>" class="wps-nowplaying-show-image" />
<% } %>
<h3 class="wps-nowplaying-title"><%= title %></h3>
<p class="wps-nowplaying-desc"><%= description %></p>
<div class="wps-nowplaying-profile">
<% _.each(profiles, function (profile) { %>
  <% if (profile.image != false) { %>
    <img src="<%= profile.image %>" class="wps-nowplaying-profile-image" />
  <% } %>
  <h3 class="wps-nowplaying-profile-name"><span>Host: </span><%= profile.name %></h3>
  <span class="wps-nowplaying-profile-bio"><%= profile.bio %></span>
  <% if (profile.facebook != false || profile.twitter != false) { %>
    <h4>Social</h4>
    <a href="http://www.facebook.com/<%= profile.facebook %>" class="wps-nowplaying-profile-facebook" target="_blank">Facebook</a>
    <a href="https://twitter.com/<%= profile.twitter %>" class="wps-nowplaying-profile-twitter" target="_blank">Twitter</a>
  <% } %>
<% }); %>
</div>
</script>
