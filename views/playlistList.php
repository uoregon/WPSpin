<?php namespace WPSpin;
/**
 * Lists previous playlists given a show ID
 */

$args = shortcode_atts(array( 	
								'show_id' => false,
								'number' => 10
							), $atts);
if(!$args['show_id']) {
	return "Error: Show ID not specified in shortcode.";
} else {
	$output = '';
	// Get list from Spinitron
	$spinpapi = SpinConnectSingleton::getInstance();
	$query['method'] = "getPlaylistsInfo";
	$query['ShowID'] = $args['show_id'];
	$query['Num'] = $args['number'];
	
	$results = $spinpapi->query($query);

	foreach($results['results'] as $playlist) {
		$startTime = strtotime($playlist['PlaylistDate'] . ' ' . $playlist['OnairTime']);
		$endTime = strtotime($playlist['PlaylistDate'] . ' ' . $playlist['OffairTime']);
		$output .= '<a href="http://spinitron.com/radio/playlist.php?station=kwva&playlist=' . $playlist['PlaylistID'] . '">';
		$output .= date('M jS g:i a', $startTime) . ' to ' . date('g:i a', $endTime) . '<br />';
		$output .= '</a>';
	}

	return $output;
}

?>