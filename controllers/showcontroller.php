<?php namespace WPSpin;

class ShowController extends ControllerAbstract
{

  public static $view;

  public static function initActions()
  {
    register_post_type( 'wpspin_shows',
      array(
        'labels' => array(
          'name' => 'Radio Shows',
          'singular_name' => 'Radio Show',
          'add_new' => '',
          'add_new_item' => '',
          'edit' => 'Edit',
          'edit_item' => 'Edit Show',
          'new_item' => '',
          'view' => 'View',
          'view_item' => 'View Show',
          'search_items' => 'Search Shows',
          'not_found' => 'No Shows found',
          'not_found_in_trash' => 'No Shows found in Trash',
          'parent' => 'Parent Radio Show'
        ),
        'public' => true,
        'menu_position' => 25,
        'supports' => array( 'title', 'editor', 'thumbnail'),
        'taxonomies' => array( '' ),
        'menu_icon' => '', 
        'has_archive' => true
      )
    );

    /* Fire our meta box setup function on the post editor screen. */
    add_action( 'load-post.php', 'WPSpin\ShowController::metaBoxSetup' );
    add_action( 'load-post-new.php', 'WPSpin\ShowController::metaBoxSetup' );
    add_action( 'admin_menu', 'WPSpin\ShowController::adjustTheWPMenu', 999 );

    self::$view = __DIR__ . "/../views/showmetabox.php";
  }

  public static function adjustTheWPMenu() {
      $page = remove_submenu_page( 'edit.php?post_type=wpspin_shows', 'post-new.php?post_type=wpspin_shows' );
  }


  /**
   * metaBoxSetup
   *
   * @static
   * @access public
   * @return void
   */
  public static function metaBoxSetup()
  {
    add_action( 'add_meta_boxes', 'WPSpin\ShowController::addMetaBox' );
    add_action( 'save_post', 'WPSpin\ShowController::metaBoxSave', 10, 2 );
  }

  /**
   * addMetaBox
   *
   * @static
   * @access public
   * @return void
   */
  public static function addMetaBox()
  {
      add_meta_box(
        'wpspin-show-options',// Unique ID
        esc_html__( 'Show Options'),// Title
        'WPSpin\ShowController::optionsMetaBox',// Callback formunction
        'wpspin_shows',// Admin page (or post type)
        'normal',// Context
        'default'// Priority
      );
  }

  /**
   * getDJNames
   *
   * @param mixed $id
   * @static
   * @access private
   * @return void
   */
  private static function getDJNames($id)
  {
    $output = "";
    $djs = unserialize(get_post_meta($id, '_wpspin_show_djs', true));
    foreach ($djs as $dj)
    {
      $output .= $dj['DJName'] . " ";
    }
    return $output;

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
    $url = admin_url() . "edit.php?s={$search_url}&post_type=wpspin_profiles";
    $html = "<a href={$url}>{$search}</a>";
    return $html;
  }

  public static function optionsMetaBox($object, $box)
  {
    include self::$view;
  }

  public static function metaBoxSave($post_id, $post)
  {
    //Verify the nonce

    if( !isset($_POST['wpspin_show_options_nonce']) || !wp_verify_nonce($_POST['wpspin_show_options_nonce'], basename(self::$view)))
    {
      return $post_id;
    }

    $post_type = get_post_type_object($post->post_type);

    if (!current_user_can($post_type->cap->edit_post, $post_id))
    {
      return $post_id;
    }

    $new_meta_values = array(
      'showurl' => esc_html($_POST['wpspin-show-options-showurl']),
    );
    $meta_keys = array(
      'showurl' => 'wpspin_show_showurl',
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
