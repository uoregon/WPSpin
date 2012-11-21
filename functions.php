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

function register_kwva_playlist_widget() {
	register_widget('KWVAPlaylist');
}

add_action( 'widgets_init', 'register_kwva_playlist_widget' );

?>
