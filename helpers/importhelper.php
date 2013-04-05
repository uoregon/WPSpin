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
   * Run updates
   * @param  boolean $setting [description]
   * @return [type]           [description]
   */
  public function update()
  {
    $shows = ShowModel::getAllShows();
    if (count($shows) == 0) {
      throw new \Exception("Failed to retrieve all the shows from Spinitron. Check your settings.");
    }
    foreach ($shows as $show)
    {
      $this->updateShow($show);
    }
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
    if (count($shows) == 0) {
      throw new \Exception("Failed to retrieve all the shows from Spinitron. Check your settings.");
    }
    foreach ($shows as $show)
    {
      $this->createShow($show);
    }
    $profiles = ProfileModel::getAllDJProfiles();

    if (count($profiles) == 0) {
      throw new \Exception("Failed to retrieve all the DJ profiles from Spinitron. Check your settings.");
    }

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
      //'tags_input'     => $this->tagifyShowUsers($show['ShowUsers']) . "{$show['ShowID']}" //For tags.
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
   * Update all show descriptions from Spinitron
   * @param  array  $show [description]
   * @return [type]       [description]
   */
  private function updateShow(array $show)
  {
    if ($this->notExists('_wpspin_show_id', $show['ShowID'], 'wpspin_shows'))
    {
      $this->createShow($show);
      return;
    }
    $query = $this->query_meta('_wpspin_show_id', $show['ShowID'], 'wpspin_shows');
    if ($query->have_posts()) {
      $post = $query->next_post();
      $content = $post->post_content;
      $updatedPost = array(
        'ID' => $post->ID,
        'post_content' => $show['ShowDescription'],
        );
      if (wp_update_post($updatedPost) == 0)
      {
        throw new \Exception("Failed to update existing post from new data in Spinitron");
      }

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
      //'tags_input'     => str_replace(" ", ", ", $profile['ShowName']), //For tags.
    );
    $id = wp_insert_post( $post );
    if ($id != 0)
    {
      update_post_meta($id, "_wpspin_profile_show_id", $profile['ShowID']);
      update_post_meta($id, "_wpspin_profile_id", $profile['UserID']);
      update_post_meta($id, "_wpspin_profile_show_name", $profile['ShowName']);
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
    return $this->notExists('_wpspin_show_id', $show['ShowID'], 'wpspin_shows');
  }

  /**
   * validateProfile
   *
   * @access private
   * @return boolean
   */

  private function validateProfile(array $profile)
  {
    return $this->notExists('_wpspin_profile_id', $profile['UserID'], 'wpspin_profiles');
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
  private function notExists($metaKey, $metaValue, $postType)
  {
    $query = $this->query_meta($metaKey, $metaValue, $postType);

    if ($query->have_posts())
    {
      return false;
    }
    return true;

  }

  private function query_meta($metaKey, $metaValue, $postType)
  {
    $params = array(
      'meta_key' => $metaKey,
      'meta_value' => "{$metaValue}",
      'meta_compare' => '=',
      'post_type' => $postType,
      );
    return new \WP_Query($params);
  }

}

?>
