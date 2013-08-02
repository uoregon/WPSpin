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
    include __DIR__ . '/../views/showschedule.php';
  }

}

?>
