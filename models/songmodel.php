<?php namespace WPSpin;

class SongModel extends ModelAbstract
{
  private static $spinpapi; //SpinPapi Instance

  private function __construct(){
  }

  public static function getInstance() {
    self::$spinpapi = SpinConnectSingleton::getInstance();
  }

  public static function getNowPlaying() {
    self::getInstance();
    $query['method'] = "getSong";
    print_r(self::$spinpapi->query($query));
  }
  

}

?>
