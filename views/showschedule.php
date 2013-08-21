<?php namespace WPSpin;

// get program schedule from Spinitron
$station = SettingsModel::getStation();
$data = file_get_contents('http://spinitron.com/radio/playlist.php?station=' . $station . '&show=schedule&ptype=s');

// remove some header information that we're not using
$data = preg_replace('/<p id="catselector">.*\n.*\n.*/', '', $data);

// Remove links
// Links look like: <a href="playlist.php?station=kwva&amp;ptype=s&amp;showid=380" title="Democracy Now! playlists">Democracy Now!</a>
// TODO: Figure out how to make these links go to our custom posts for show/dj. Something like:
// $data = preg_replace('/playlist.php?.*showid=/', '?page_id=', $data);
$data = preg_replace('/<a\b[^>]*>/', '<a href="wpspin_profiles/">', $data);
// $data = preg_replace('/<\/a>/', '', $data);

// link stylesheet from view and render processed data
$stylesheet = plugin_dir_url( __FILE__ ) . '../views/showschedule.css';
print('<link rel="stylesheet" href="'. $stylesheet .'" />');
print($data);
?>
