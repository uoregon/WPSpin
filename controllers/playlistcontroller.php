<?php namespace WPSpin;

class PlaylistController extends ControllerAbstract
{
  public static function initActions()
  {
    add_action( 'wp_ajax_retrieve-playlist', 'WPSpin\PlaylistController::retrievePlaylist' );
    add_action( 'wp_ajax_nopriv_retrieve-playlist', 'WPSpin\PlaylistController::retrievePlaylist' );
    add_action( 'wp_ajax_get-all-shows', 'WPSpin\PlaylistController::getAllShows' );
    add_action( 'wp_ajax_nopriv_get-all-shows', 'WPSpin\PlaylistController::getAllShows' );
    add_action( 'wp_ajax_get-all-profiles', 'WPSpin\PlaylistController::getAllDJProfiles' );
    add_action( 'wp_ajax_nopriv_get-all-profiles', 'WPSpin\PlaylistController::getAllDJProfiles' );

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


}

?>
