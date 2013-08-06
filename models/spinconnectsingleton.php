<?php namespace WPSpin;

date_default_timezone_set(SettingsModel::getTimezone());

class SpinConnectSingleton {

  private static $spinpapi;


  private function __construct() {
  }

  public static function getInstance()
  {
    $userid = SettingsModel::getApiKey();
    $secret = SettingsModel::getSecretKey();
    $station = SettingsModel::getStation();
    $spinpapiCacheFolder = dirname(__FILE__) . '/../spinpapi/tmp';
    $logerrors = true;
    $apiVersion = 2;

    if (!isset(self::$spinpapi))
    {
      self::$spinpapi = new \SpinPapiClient($userid, $secret, $station, $logerrors, $apiVersion);
      self::$spinpapi->fcInit($spinpapiCacheFolder);
    }
    return self::$spinpapi;
  }
}

?>
