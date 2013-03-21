<?php namespace WPSpin;

class ProfileController extends ControllerAbstract
{
  public static $view;

  /**
   * initActions
   *
   * Initialize WPSpin Profiles custom post type
   * Call actions on initializing metaboxes
   *
   * @static
   * @access public
   * @return void
   */
  public static function initActions()
  {
    register_post_type( 'wpspin_profiles',
      array(
        'labels' => array(
          'name' => 'DJ Profiles',
          'singular_name' => 'DJ Profile',
          'add_new' => '',
          'add_new_item' => '',
          'edit' => 'Edit',
          'edit_item' => 'Edit Profile',
          'new_item' => '',
          'view' => 'View',
          'view_item' => 'View Profile',
          'search_items' => 'Search Profiles',
          'not_found' => 'No Profiles found',
          'not_found_in_trash' => 'No Profiles found in Trash',
          'parent' => 'Parent DJ Profile'
        ),
        'public' => true,
        'menu_position' => 25,
        'supports' => array( 'title', 'editor', 'thumbnail' ),
        'taxonomies' => array( '' ),
        'menu_icon' => '', 
        'has_archive' => true
      )
    );
    /* Fire our meta box setup function on the post editor screen. */
    add_action( 'load-post.php', 'WPSpin\ProfileController::metaBoxSetup' );
    add_action( 'load-post-new.php', 'WPSpin\ProfileController::metaBoxSetup' );
    add_action( 'admin_menu', 'WPSpin\ProfileController::adjustTheWPMenu', 999 );

    // Specify the view path and filename
    
    self::$view = __DIR__ . "/../views/profilemetabox.php";
  }

  public static function adjustTheWPMenu() {
      $page = remove_submenu_page( 'edit.php?post_type=wpspin_profiles', 'post-new.php?post_type=wpspin_profiles' );
  }

  /**
   * metaBoxSetup
   *
   * Add actions for adding metaboxes and saving metabox data hooks
   *
   * @static
   * @access public
   * @return void
   */
  public static function metaBoxSetup()
  {
    add_action( 'add_meta_boxes', 'WPSpin\ProfileController::addMetaBox' );
    add_action( 'save_post', 'WPSpin\ProfileController::metaBoxSave', 10, 2 );
  }

  /**
   * addMetaBox
   *
   * Add the metabox using Wordpress function
   *
   * @static
   * @access public
   * @return void
   */
  public static function addMetaBox()
  {
      add_meta_box(
        'wpspin-profile-options',// Unique ID
        esc_html__( 'Profile Options'),// Title
        'WPSpin\ProfileController::optionsMetaBox',// Callback formunction
        'wpspin_profiles',// Admin page (or post type)
        'normal',// Context
        'default'// Priority
      );
  }

  /**
   * linkify
   *
   * @param mixed $search
   * @static
   * @access public
   * @return void
   */

  public static function linkify($search)
  {
    $search_url = str_replace(" ", "+", $search);
    $url = admin_url() . "edit.php?s={$search_url}&post_type=wpspin_shows";
    $html = "<a href={$url}>{$search}</a>";
    return $html;
  }

  /**
   * optionsMetaBox
   *
   * The template for the custom metabox options shown in the new Profile custom post type
   *
   * @param mixed $object
   * @param mixed $box
   * @static
   * @access public
   * @return void
   */
  public static function optionsMetaBox($object, $box)
  {
    include self::$view;
  }

  /**
   * metaBoxSave
   *
   * Saving handler to check nonce and user permissions. Also saves the data when things check out.
   *
   * @param mixed $post_id
   * @param mixed $post
   * @static
   * @access public
   * @return void
   */
  public static function metaBoxSave($post_id, $post)
  {
    //Verify the nonce

    if( !isset($_POST['wpspin_profile_options_nonce']) || !wp_verify_nonce($_POST['wpspin_profile_options_nonce'], basename(self::$view)))
    {
      return $post_id;
    }

    $post_type = get_post_type_object($post->post_type);

    if (!current_user_can($post_type->cap->edit_post, $post_id))
    {
      return $post_id;
    }

    $new_meta_values = array(
      'twitter' => esc_html($_POST['wpspin-profile-options-twitter']), 
      'facebook' => esc_html($_POST['wpspin-profile-options-facebook']),
    );
    $meta_keys = array(
      'twitter' => 'wpspin_profile_twitter',
      'facebook' => 'wpspin_profile_facebook',
    );

    foreach ($meta_keys as $field => $meta_key)
    {
      $meta_value = get_post_meta( $post_id, $meta_key, true );

      if ( $new_meta_values[$field] && '' == $meta_value )
      {
        delete_post_meta( $post_id, $meta_key, $meta_value );
        add_post_meta( $post_id, $meta_key, $new_meta_values[$field], true );
      }
      elseif ( $new_meta_values[$field] && $new_meta_values[$field] != $meta_value )
      {
        update_post_meta( $post_id, $meta_key, $new_meta_values[$field] );
      }
      elseif ( '' == $new_meta_values[$field] && $meta_value )
      {
        delete_post_meta( $post_id, $meta_key, $meta_value );
      }
    }
  }
}

?>
