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
  	$data = file_get_contents('http://spinitron.com/radio/playlist.php?station=kwva&show=schedule&ptype=s');
	$stylesheet = plugin_dir_url( __FILE__ ) . '../views/showschedule.css';

	// link stylesheet from view and render processed data
	print('<link rel="stylesheet" href="'. $stylesheet .'" />');
	print($data);
  }

}

?>
