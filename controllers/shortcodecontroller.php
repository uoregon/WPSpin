<?php namespace WPSpin;

class ShortcodeController extends ControllerAbstract
{

  public static function initActions()
  {
    add_shortcode('wpspin-now-playing', 'WPSpin\ShortcodeController::nowPlaying');
    add_shortcode('wpspin-show-schedule', 'WPSpin\ShortcodeController::showSchedule');
  }

  public static function nowPlaying()
  {
    include __DIR__ . '/../views/nowplaying.php';
  }

  public static function showSchedule()
  {
  	$station = SettingsModel::getStation();
  	$data = file_get_contents('http://spinitron.com/radio/playlist.php?station=' . $station . '&show=schedule&ptype=s');
	$stylesheet = plugin_dir_url( __FILE__ ) . '../views/showschedule.css';

	// Remove links
	// Links look like: <a href="playlist.php?station=kwva&amp;ptype=s&amp;showid=380" title="Democracy Now! playlists">Democracy Now!</a>
	// TODO: Figure out how to make these links go to our custom posts for show/dj. Something like:
	// $data = preg_replace('/playlist.php?.*showid=/', '?page_id=', $data);
	$data = preg_replace('/<a\b[^>]*>/', '', $data);
	$data = preg_replace('/<\/a>/', '', $data);

	// link stylesheet from view and render processed data
	print('<link rel="stylesheet" href="'. $stylesheet .'" />');
	print($data);
  }

}

?>
