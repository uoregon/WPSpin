<?php namespace WPSpin;

class ShowModel extends ModelAbstract implements ApiAccessInterface
{
  private static $spinpapi;

  public static function getApiInstance() {
    self::$spinpapi = SpinConnectSingleton::getInstance();
  }

  public static function getAllShows()
  {
    self::getApiInstance();
    $query['method'] = "getRegularShowsInfo";
    $results = self::$spinpapi->query($query);
    return $results['results'];
  } 

}

?>
