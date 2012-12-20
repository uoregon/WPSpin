<?php namespace WPSpin;
/**
 * Setup function. Creates required table if not found.
 * 
 * @global type $wpdb
 */

class ProfileModel extends ModelAbstract implements ApiAccessInterface
{
//      `showID` int PRIMARY KEY,
//      `showName` VARCHAR(64) NOT NULL,
//      `description` TEXT,
//      `image` VARCHAR(64),
//      `twitter` VARCHAR(32),
//      `facebook` VARCHAR(128),
//      `website` VARCHAR(128),
//      `active` BOOLEAN NOT NULL)
//

  private static $spinpapi;

  public static function getApiInstance() {
    self::$spinpapi = SpinConnectSingleton::getInstance();
  }

  public static function getAllDJProfiles()
  {
    $shows = ShowModel::getAllShows();
    $profiles = array();
    foreach ($shows as $show) 
    {
      foreach ($show['ShowUsers'] as $user)
      {
        $profiles[] = array(
          'ShowID' => $show['ShowID'],
          'UserID' => $user['UserID'],
          'DJName' => $user['DJName'],
          'ShowName' => $show['ShowName'],
          'ShowDescription' => $show['ShowDescription'],
          'ShowURL' => $show['ShowURL'],
          'ShowCategory' => $show['ShowCategory'],
        );
      }
      
    }
    return $profiles;
  } 

}

?>
