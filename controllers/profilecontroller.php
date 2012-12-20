<?php namespace WPSpin;

class ProfileController extends ControllerAbstract
{
  public static function initActions()
  {
    register_post_type( 'wpspin_profiles',
      array(
        'labels' => array(
          'name' => 'DJ Profiles',
          'singular_name' => 'DJ Profile',
          'add_new' => 'Add New DJ Profile',
          'add_new_item' => 'Add New DJ Profile',
          'edit' => 'Edit',
          'edit_item' => 'Edit DJ Profile',
          'new_item' => 'New DJ Profile',
          'view' => 'View',
          'view_item' => 'View DJ Profile',
          'search_items' => 'Search DJ Profiles',
          'not_found' => 'No DJ Profiles found',
          'not_found_in_trash' => 'No DJ Profiles found in Trash',
          'parent' => 'Parent DJ Profile'
        ),
        'public' => true,
        'menu_position' => 25,
        'supports' => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
        'taxonomies' => array( '' ),
        'menu_icon' => '', 
        'has_archive' => true
      )
    );

  }
  public static function initAdminActions()
  {
  }

}

?>
