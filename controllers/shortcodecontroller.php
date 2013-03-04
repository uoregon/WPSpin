<?php namespace WPSpin;

class ShortcodeController extends ControllerAbstract
{

  public static function initActions()
  {
    add_shortcode('wpspin-now-playing', 'WPSpin\ShortcodeController::nowPlaying');
  }

  public static function nowPlaying()
  {
    include __DIR__ . '/../views/nowplaying.php';
  }

}

?>
