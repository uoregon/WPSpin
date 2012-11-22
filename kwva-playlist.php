<?php namespace KWVAPlaylist;

class KWVAPlaylist extends \WP_Widget { 

	public function __construct() {
		parent::__construct( 
		'kwva_playlist',
	        'KWVA Playlist',
		array(
		    'classname' => 'kwva_playlist',
		    'description' => __('Use this widget For KWVA Playlist Updates From Spinitron'),
		    )
		);
	}

	public function widget($args, $instance) {
		extract($args);
		$title = apply_filters( 'widget_title', 'KWVA Playlist' );
		echo $before_widget;
		if ( ! empty( $title ) )
			echo $before_title . $title . $after_title;
		echo '<div class="kwva-playlist-items"></div>';
		echo $after_widget;
	}

	public function update($instance) {

	}

	public function form($new_instance, $old_instance) {

	}

}
?>
