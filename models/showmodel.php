<?php namespace WPSpin;

class ShowModel extends ModelAbstract
{
  private static $spinpapi;

  /**
   * getApiInstance
   *
   * @static
   * @access public
   * @return void
   */

  public static function getApiInstance() {
    self::$spinpapi = SpinConnectSingleton::getInstance();
  }

  /**
   * getAllShows
   *
   * @static
   * @access public
   * @return void
   */

  public static function getAllShows()
  {
    self::getApiInstance();
    $query['method'] = "getRegularShowsInfo";
    $results = self::$spinpapi->query($query);
    return $results['results'];
  } 

  /**
   * getCurrentShow
   *
   * @static
   * @access public
   * @return void
   */

  public static function getCurrentShow()
  {
    $show = array();
    self::getApiInstance();
    $query['method'] = "getShowInfo";
    $results = self::$spinpapi->query($query);
    $showinfo = $results['results'];
    $showID = $showinfo['ShowID'];
    $posts = self::getShowPosts($showID);
    if (count($posts) > 0)
    {
      $post = $posts[0];
      $show['description'] = $post->post_content;
      $show['title'] = $post->post_title;
      $show['DJProfiles'] = self::getShowDJs($post);
    }
    return $show;

  }

  /**
   * getShowPosts
   *
   * @param mixed $showID
   * @static
   * @access private
   * @return void
   */
  private static function getShowPosts($showID)
  {
    $posts = get_posts(array(
      'post_type' => 'wpspin_shows',
      'meta_key' => '_wpspin_show_id',
      'meta_value' => "{$showID}",
      'compare' => '=',
    ));
    return $posts;
  }

  /**
   * getShowDJs
   *
   * @param \WP_Post $post
   * @static
   * @access private
   * @return void
   */
  private static function getShowDJs(\WP_Post $post)
  {
    $djs = array();
    $showDJs = unserialize(self::getMetaData($post->ID, '_wpspin_show_djs'));
    foreach ($showDJs as $dj)
    {
      $djs[] = ProfileModel::getDJInfo($dj['UserID']);
    }
    return $djs;
  }


}

?>
