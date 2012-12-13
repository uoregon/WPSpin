<?php namespace WPSpin;

class PlaylistModel extends ModelAbstract implements ApiAccessInterface
{
  private static $spinpapi;

  public static function getApiInstance() {
    self::$spinpapi = SpinConnectSingleton::getInstance();
  }

  public static function getPlaylistSongs() {
    self::getApiInstance();
    $query['method'] = "getSongs";
    $query['Num'] = 10;
    $results = self::$spinpapi->query($query);
    return $results['results'];
  }
}

?>
