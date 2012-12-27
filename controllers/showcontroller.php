<?php namespace WPSpin;

class ShowController extends ControllerAbstract
{
  public static function initActions()
  {
    register_post_type( 'wpspin_shows',
      array(
        'labels' => array(
          'name' => 'Radio Shows',
          'singular_name' => 'Radio Show',
          'add_new' => 'Add New Show',
          'add_new_item' => 'Add New Show',
          'edit' => 'Edit',
          'edit_item' => 'Edit Show',
          'new_item' => 'New Show',
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
        'taxonomies' => array( 'post_tag' ),
        'menu_icon' => '', 
        'has_archive' => true
      )
    );

    /* Fire our meta box setup function on the post editor screen. */
    add_action( 'load-post.php', 'WPSpin\ShowController::metaBoxSetup' );
    add_action( 'load-post-new.php', 'WPSpin\ShowController::metaBoxSetup' );
  }

  public static function metaBoxSetup()
  {
    add_action( 'add_meta_boxes', 'WPSpin\ShowController::addMetaBox' );
    add_action( 'save_post', 'WPSpin\ShowController::metaBoxSave', 10, 2 );
  }

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

  private static function getDJNames($id)
  {
    $output = "";
    $djs = unserialize(get_post_meta($id, '_wpspin_show_djs', true));
    foreach ($djs as $dj)
    {
      $output .= $dj['DJName'] . ", ";
    }
    return $output;

  }

  public static function optionsMetaBox($object, $box)
  {

//Begin Options Metabox Template ?>

<?php wp_nonce_field( basename( __FILE__ ), 'wpspin_show_options_nonce' ); ?>

<table>
<tr>
<td>
  <label for="wpspin-show-options-showurl"><?php _e( "Show URL" ); ?></label>
</td>
<td>
<input class="widefat" type="text" name="wpspin-show-options-showurl" id="wpspin-show-options-showurl" value="<?php echo esc_attr( get_post_meta( $object->ID, 'wpspin_show_showurl', true ) ); ?>" size="30" />
</td>
</tr>
<tr>
  <td><?php _e( "Show ID" ) ?></td>
  <td><?php echo esc_attr( get_post_meta( $object->ID, '_wpspin_show_id', true ) ) ?> </td> 
</tr>
<tr>
  <td><?php _e( "DJs" ) ?></td>
  <td><?php echo esc_html( self::getDJNames($object->ID) ) ?></td>
</tr>
</table>

<?php //End Options Metabox Template

  }

  public static function metaBoxSave($post_id, $post)
  {
    //Verify the nonce
    if( !isset($_POST['wpspin_show_options_nonce']) || !wp_verify_nonce($_POST['wpspin_show_options_nonce'], basename(__FILE__)))
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
