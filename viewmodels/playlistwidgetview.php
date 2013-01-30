<?php namespace WPSpin;

class PlaylistWidgetView extends \WP_Widget { 

  public function __construct() {
    parent::__construct( 
      'wps_playlist',
      'WPSpin Playlist',
      array(
        'classname' => 'wps_playlist',
        'description' => __('Use this widget For Playlist Updates From Spinitron'),
      )
    );
  }

  public function widget($args, $instance) {
    extract($args);
    $title = apply_filters( 'widget_title', 'Playlist' );
    echo $before_widget;
    if ( ! empty( $title ) ) {
      echo $before_title . $title . $after_title;
      echo '<section class="wps-playlist-items"></section>';
?>
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
<?php
    }
    echo $after_widget;
  }

  public function update($instance) {

  }

  public function form($new_instance, $old_instance) {

  }

  public static function initPlaylistWidget() {
    register_widget("WPSpin\PlaylistWidgetView");
  }
}

?>
