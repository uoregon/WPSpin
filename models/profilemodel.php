<?php namespace WPSpin;
/**
 * Setup function. Creates required table if not found.
 * 
 * @global type $wpdb
 */

class ProfileModel extends ModelAbstract
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

  /**
   * getAllDJProfiles
   *
   * @static
   * @access public
   * @return void
   */

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

  private static function getDJPostData($userID)
  {
    $posts = get_posts(array(
      'post_type' => 'wpspin_profiles',
      'meta_key' => '_wpspin_profile_id',
      'meta_value' => "{$userID}",
      'compare' => '=',
    ));
    return $posts;
  }

  public static function getDJInfo($userID)
  {
    $dj = array();
    $posts = self::getDJPostData($userID);
    $post = $posts[0];
    $dj['name'] = $post->post_title;
    $dj['bio'] = $post->post_content;
    $dj['facebook'] = self::getMetaData($post->ID, 'wpspin_profile_facebook');
    $dj['twitter'] = self::getMetaData($post->ID, 'wpspin_profile_twitter');
    $dj['image'] = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
    return $dj;
  }

}

?>
