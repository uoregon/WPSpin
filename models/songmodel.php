<?php namespace WPSpin;

class SongModel extends ModelAbstract implements ApiAccessInterface
{
  private static $spinpapi; //SpinPapi Instance

  private function __construct(){
  }

  public static function getApiInstance() {
    self::$spinpapi = SpinConnectSingleton::getInstance();
  }

  public static function getNowPlaying() {
    self::getApiInstance();
    $query['method'] = "getSong";
    print_r(self::$spinpapi->query($query));
  }
  

}

?>
