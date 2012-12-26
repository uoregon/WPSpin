<?php namespace WPSpin;
   /*
   Plugin Name: WPSpin
   Plugin URI: http://github.com/emumark/WPSpin
   Description: Provides Wordpress Spinitron integraton through to SPINPAPI API
   Version: 0.5
   Author: Michael A Tomcal - University of Oregon - EMU Marketing
   Author URI: http://marketing.uoregon.edu
   License: MIT
   Copyright 2012  University of Oregon (email : emumark@uoregon.edu)

   Permission is hereby granted, free of charge, to any person obtaining a copy 
   of this software and associated documentation files (the "Software"), to deal 
   in the Software without restriction, including without limitation the rights 
   to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies 
   of the Software, and to permit persons to whom the Software is furnished to do so, 
   subject to the following conditions:

   The above copyright notice and this permission notice shall be included in 
   all copies or substantial portions of the Software.

   THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR 
   IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, 
   FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE 
   AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, 
   WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN 
   CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

//SpinPapi Requires
require_once 'spinpapi/SpinPapiClient.inc.php';

//Model Requires
require_once 'models/apiaccessinterface.php';
require_once 'models/dbaccessinterface.php';
require_once 'models/modelabstract.php';
require_once 'models/settingsmodel.php';
require_once 'models/spinconnectsingleton.php';
require_once 'models/songmodel.php';
require_once 'models/playlistmodel.php';
require_once 'models/showmodel.php';
require_once 'models/profilemodel.php';

//Controller Requires
require_once 'controllers/controllerabstract.php';
require_once 'controllers/playlistcontroller.php';
require_once 'controllers/profilecontroller.php';
require_once 'controllers/showcontroller.php';

//ViewModel Requires
require_once 'viewmodels/viewmodelinterface.php';
require_once 'viewmodels/playlistwidgetview.php';
require_once 'viewmodels/settingsview.php';

//Helper Requires
require_once 'helpers/importhelper.php';

global $wpdb;

add_action( 'widgets_init', 'WPSpin\PlaylistWidgetView::initPlaylistWidget');

function scriptRegistry($name_location_array) {
	foreach ($name_location_array as $name => $location) {
		wp_deregister_script($name);	
		wp_register_script($name, $location, array('jquery'));
		wp_enqueue_script($name);
    wp_localize_script( $name, 'WPSpinAjax', array( url => admin_url( 'admin-ajax.php' ) ) );
  }
}


function playlistScripts() {
	$url = plugins_url('WPSpin');
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
	scriptRegistry($scripts_array);

}    
 
add_action('wp_enqueue_scripts', 'WPSpin\playlistScripts');

/**
 * Settings View Registration for Options page in Admin area
 */

global $settings;

/**
 * adminMenu
 *
 * Instantiate SettingsView and provide a render callback and sanitization callback
 *
 * @access public
 * @return void
 */
function adminMenu()
{
  global $settings;
  $settings = new SettingsView('WPSpin\renderSettings', 'WPSpin\sanitizeSettingsMenu');
}

/**
 * sanitizeSettingsMenu
 *
 * Sanitization callback for settings functions
 * Used to run imports for shows and profiles as well. 
 *
 * @param mixed $options
 * @access public
 * @return void
 */
function sanitizeSettingsMenu($options)
{
  if ($options['import'] == 1)
  {
    $import = new ImportHelper();
    $import->import();
  }
  return $options;
}

/**
 * renderSettings
 *
 * Render the settings page template
 *
 * @access public
 * @return void
 */

function renderSettings()
{
  global $settings;
  $settings->render();
}

/**
 * Initialize admin menu
 */
add_action("admin_menu", 'WPSpin\adminMenu');

//Initialize Controllers

add_action('init', 'WPSpin\ProfileController::initActions');
add_action('init', 'WPSpin\ShowController::initActions');
add_action('init', 'WPSpin\PlaylistController::initActions');

?>
