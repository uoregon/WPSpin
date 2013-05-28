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
    $query['method'] = "getRegularShowsInfo";
    $query['When'] = 'now';
    $results = self::$spinpapi->query($query);
    $showinfo = $results['results'];
    $showID = $showinfo[0]['ShowID'];
    $posts = self::getShowPosts($showID);
    if (count($posts) > 0)
    {
      $post = $posts[0];
      $show['description'] = $post->post_content;
      $show['title'] = $post->post_title;
      $show['image'] = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
      $show['DJProfiles'] = self::getShowDJs($post);
    } else {
      $show['title'] = $showinfo[0]['ShowName'];
      $show['description'] = $showinfo[0]['ShowDescription'];
      $show['image'] = '';
      $show['DJProfiles'] = self::getSpinShowDJs($showinfo[0]['ShowUsers']);
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

   /**
   * getSpinShowDJs
   *
   * @param array $post
   * @static
   * @access private
   * @return void
   */
  private static function getSpinShowDJs(array $showDJs)
  {
    $djs = array();
    foreach ($showDJs as $dj)
    {
      $djs[] = array(
        'name' => $dj['DJName'],
        'bio' => '',
        'facebook' => '',
        'twitter' => '',
        'image' => '',
        );
    }
    return $djs;
  }


}

?>
