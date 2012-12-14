<?php namespace WPSpin;

class PlaylistController extends ControllerAbstract
{
  public static function initActions()
  {
    add_action( 'wp_ajax_retrieve-playlist', 'WPSpin\PlaylistController::retrievePlaylist' );
    add_action( 'wp_ajax_nopriv_retrieve-playlist', 'WPSpin\PlaylistController::retrievePlaylist' );
  }

  public static function retrievePlaylist()
  {
    $response = trim(json_encode(PlaylistModel::getPlaylistSongs()));
    header( "Content-Type: application/json" );
    echo $response;
    die();
  }

}

?>
