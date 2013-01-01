<?php namespace WPSpin;

class AjaxController extends ControllerAbstract
{
  public static function initActions()
  {
    add_action( 'wp_ajax_retrieve-playlist', 'WPSpin\AjaxController::retrievePlaylist' );
    add_action( 'wp_ajax_nopriv_retrieve-playlist', 'WPSpin\AjaxController::retrievePlaylist' );
    add_action( 'wp_ajax_get-all-shows', 'WPSpin\AjaxController::getAllShows' );
    add_action( 'wp_ajax_nopriv_get-all-shows', 'WPSpin\AjaxController::getAllShows' );
    add_action( 'wp_ajax_get-all-profiles', 'WPSpin\AjaxController::getAllDJProfiles' );
    add_action( 'wp_ajax_nopriv_get-all-profiles', 'WPSpin\AjaxController::getAllDJProfiles' );
    add_action( 'wp_ajax_get-current-show', 'WPSpin\AjaxController::getCurrentShow' );
    add_action( 'wp_ajax_nopriv_get-current-show', 'WPSpin\AjaxController::getCurrentShow' );
  }

  public static function retrievePlaylist()
  {
    $response = json_encode(PlaylistModel::getPlaylistSongs());
    echo $response;
    die();
  }

  public static function getAllShows()
  {
    $response = json_encode(ShowModel::getAllShows());
    echo $response;
    die();
  }

  public static function getAllDJProfiles()
  {
    $response = json_encode(ProfileModel::getAllDJProfiles());
    echo $response;
    die();
  }

  public static function getCurrentShow()
  {
    $response = json_encode(ShowModel::getCurrentShow());
    echo $response;
    die();
  }

}

?>
