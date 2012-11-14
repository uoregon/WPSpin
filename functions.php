<?php namespace KWVAPlaylist;
   /*
   Plugin Name: KWVA Playlist
   Plugin URI: http://marketing.uoregon.edu
   Description: Provides the playlist for KWVA as fed from Spinitron
   Version: 0.1
   Author: Michael A Tomcal
   Author URI: https://github.com/mtomcal
    */

require_once 'kwva-playlist.php';

function register_kwva_playlist_widget() {
	register_widget('KWVAPlaylist');
}

add_action( 'widgets_init', 'register_kwva_playlist_widget' );

?>
