<?php namespace KWVAPlaylist;
   /*
   Plugin Name: KWVA Playlist
   Plugin URI: http://github.com/emumark/REPONAMEHERE
   Description: Provides the playlist for KWVA as fed from Spinitron
   Version: 0.1
   Author: Michael A Tomcal - University of Oregon - EMU Marketing
   Author URI: http://marketing.uoregon.edu
   License: GPL2
   Copyright 2012  University of Oregon (email : emumark@uoregon.edu)

   This program is free software; you can redistribute it and/or modify
   it under the terms of the GNU General Public License, version 2, as 
   published by the Free Software Foundation.

   This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with this program; if not, write to the Free Software
   Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

require_once 'kwva-playlist.php';

function init_kwva_widget() {
	register_widget("KWVAPlaylist\KWVAPlaylist");
}

add_action( 'widgets_init', 'KWVAPlaylist\init_kwva_widget');

function script_registry($name_location_array) {
	foreach ($name_location_array as $name => $location) {
		wp_deregister_script($name);	
		wp_register_script($name, $location);
		wp_enqueue_script($name);
	}
}


function playlist_scripts() {
	$url = plugins_url('kwva-playlist');
	$scripts_array = array(
	    "underscore" => 'http://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.4.2/underscore-min.js',
	    "backbone" => 'http://cdnjs.cloudflare.com/ajax/libs/backbone.js/0.9.2/backbone-min.js',
	    "backbone-mediator" => $url . '/assets/backbone-mediator.js',
	    "collection" => $url . '/assets/collection.js',
	    "model" => $url . '/assets/model.js',
	    "view" => $url . '/assets/view.js',
	    "song_model" => $url . '/assets/song_model.js',
	    "playlist_item" => $url . '/assets/playlist_item.js',
	    "playlist_collection" => $url . '/assets/playlist_collection.js',
	    "listen_view" => $url . '/assets/listen_view.js',
	    );
	script_registry($scripts_array);

}    
 
add_action('wp_enqueue_scripts', 'KWVAPlaylist\playlist_scripts');


?>
