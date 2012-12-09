<?php namespace WPSpin;

require_once dirname(__FILE__) . '/../config.php';

date_default_timezone_set($timezone);

class SpinConnectSingleton {

  private static $spinpapi;

  private function __construct() {
  }

  public static function getInstance()
  {
    global $userid;
    global $secret;
    global $station;
    global $logerrors;
    global $spinpapiCacheFolder;

    if (!isset(self::$spinpapi))
    {
      self::$spinpapi = new \SpinPapiClient($userid, $secret, $station, $logerrors);
      self::$spinpapi->fcInit($spinpapiCacheFolder);
    }
    return self::$spinpapi;
  }
}

?>
