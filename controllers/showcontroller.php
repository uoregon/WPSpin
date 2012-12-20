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
          'add_new' => 'Add New Radio Show',
          'add_new_item' => 'Add New Radio Show',
          'edit' => 'Edit',
          'edit_item' => 'Edit Radio Show',
          'new_item' => 'New Radio Show',
          'view' => 'View',
          'view_item' => 'View Radio Show',
          'search_items' => 'Search Radio Shows',
          'not_found' => 'No Radio Shows found',
          'not_found_in_trash' => 'No Radio Shows found in Trash',
          'parent' => 'Parent Radio Show'
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

}

?>
