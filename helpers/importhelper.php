<?php namespace WPSpin;

/**
 * ImportHelper
 * 
 * Import DJ Profiles and Shows from Spinitron API
 *
 * @author Michael A Tomcal - EMU Marketing <emumark@uoregon.edu> 
 * @license MIT
 */
class ImportHelper
{

  /**
   * __construct
   *
   * @access public
   * @return void
   */
  public function __construct()
  {
  }

  /**
   * import
   *
   * @access public
   * @return void
   */

  public function import()
  {
    $shows = ShowModel::getAllShows();
    foreach ($shows as $show)
    {
      $this->createShow($show);
    }
    $profiles = ProfileModel::getAllDJProfiles();
    foreach ($profiles as $profile)
    {
      $this->createProfile($profile);
    }

  }

  /**
   * createShow
   *
   * @param array $show
   * @access private
   * @return void
   */
  private function createShow(array $show)
  {
    if (!$this->validateShow($show))
    {
      return;
    }
    $post = array(
      'post_content'   => $show['ShowDescription'], //The full text of the post.
      'post_status'    => 'publish', //Set the status of the new post.
      'post_title'     => $show['ShowName'], //The title of your post.
      'post_type'      => 'wpspin_shows', //You may want to insert a regular post, page, link, a menu item or some custom post type
      'tags_input'     => $this->tagifyShowUsers($show['ShowUsers']) . "{$show['ShowID']}" //For tags.
    );
    $id = wp_insert_post( $post );
    if ($id != 0)
    {
      update_post_meta($id, "_wpspin_show_id", $show['ShowID']);
      update_post_meta($id, "_wpspin_show_djs", serialize($show['ShowUsers']));
      update_post_meta($id, "wpspin_show_showurl", $show['ShowUrl']);
    }
  }

  /**
   * tagifyShowUsers
   *
   * @param array $users
   * @access private
   * @return void
   */
  private function tagifyShowUsers(array $users)
  {
    $output = "";
    foreach ($users as $user)
    {
      $output .= str_replace(" ", ", ", $user['DJName']) . ", ";
    }
    return $output;
  }

  /**
   * createProfile
   *
   * @param array $profile
   * @access private
   * @return void
   */
  private function createProfile(array $profile)
  {
    if (!$this->validateProfile($profile))
    {
      return;
    }
    $post = array(
      'post_content'   => "", //The full text of the post.
      'post_status'    => 'publish', //Set the status of the new post.
      'post_title'     => $profile['DJName'], //The title of your post.
      'post_type'      => 'wpspin_profiles', //You may want to insert a regular post, page, link, a menu item or some custom post type
      'tags_input'     => str_replace(" ", ", ", $profile['ShowName']), //For tags.
    );
    $id = wp_insert_post( $post );
    if ($id != 0)
    {
      update_post_meta($id, "_wpspin_profile_show_id", $profile['ShowID']);
      update_post_meta($id, "_wpspin_profile_id", $profile['UserID']);
    }
  }

  /**
   * validateShow
   *
   * @access private
   * @return boolean
   */

  private function validateShow(array $show)
  {
    return $this->validate('_wpspin_show_id', $show['ShowID'], 'wpspin_shows');
  }

  /**
   * validateProfile
   *
   * @access private
   * @return boolean
   */

  private function validateProfile(array $profile)
  {
    return $this->validate('_wpspin_profile_id', $profile['UserID'], 'wpspin_profiles');
  }

  /**
   * validate
   *
   * @param string $metaKey
   * @param string $metaValue
   * @param string $postType
   * @access private
   * @return boolean
   */
  private function validate($metaKey, $metaValue, $postType)
  {
    $params = array(
      'meta_key' => $metaKey,
      'meta_value' => "{$metaValue}",
      'meta_compare' => '=',
      'post_type' => $postType,
    );
    $query = new \WP_Query($params);
    if ($query->have_posts())
    {
      return false;
    }
    return true;

  }

}

?>
