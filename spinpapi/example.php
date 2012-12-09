<?php
/**
 * Example use of SpinPapiClient.
 *
 * PHP version 5.3
 *
 * LICENSE:
 *
 * Copyright (c) 2011 Spinitron
 * All rights reserved.
 *
 * Use in source and binary forms, with or without modification, are 
 * permitted. Redistribution is not. 
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * @category    SpinPapi
 * @uses        SpinPapiClient
 * @author      Tom Worster <tom@spinitron.com> 
 * @copyright   2011 Spinitron
 * @license     Proprietary. Confidential. Modification OK. No redistribution. 
 * @version     2011-09-08
 * @link        http://spinitron.com/
 */


// SpinPapiConf.inc.php has to be in your include path or you must change this to
// a path name to it. Read SpinPapiConf.template.inc.php for config instructions.
require_once 'SpinPapiConf.inc.php';

if (!isset($mySpClientPath)
	|| !(include_once $mySpClientPath)
) {
	require_once 'SpinPapiClient.inc.php';
}

// Instantiate our client object.
$sp = new SpinPapiClient($mySpUserID, $mySpSecret, $myStation, true);

// Initialize the cache according to the config file.
if (isset($myFCache)) {
	$sp->fcInit($myFCache);
}
if (isset($myMemcache)) {
	$sp->mcInit($myMemcache);
}

// A handy function for fomatting small time intervals. Presumes the HTML page 
// is encoded as UTF-8.
function my_seconds($n, $d=4) {
	if ( $n == 0 ) {
		return $n;
	} else {
		if ($n > 1) {
			$m = $n;
			$s = ' s';
			$e = max($d - 1 - floor(log($m,10)), 0);
		} elseif ($n > 0.001) {
			$m = 1000*$n;
			$s = ' ms';
			$e = min(max($d - 1 - floor(log($m,10)), 0), 3);
		} else {
			return round(1000000*$n) . ' μs';
		}
		return number_format($m, $e) . $s;
	}
}

// Shortcut to html encode a string
function e($s) { 
  return htmlspecialchars($s);
}



// Measure how long it takes for us to get the data from SpinPapi
$t = microtime(1);
$nowSong = $sp->query(array('method' => 'getSong'));
$t = my_seconds(microtime(1) - $t);

// You should perfom error checking. This example simply discards the 'success' 
// and 'request' parts of the SpinPapi responses:
$nowSong = $nowSong['results'];

// Nasty hack to store the timing info
$nowSong['t'] = $t;



$t = microtime(1);
$recentSongs = $sp->query(array('method' => 'getSongs', 'Num' => 3));
$t = my_seconds(microtime(1) - $t);
$recentSongs = $recentSongs['results'];
$recentSongs[0]['t'] = $t;



// You don't need to getPlaylistInfo for the current show if you already have 
// the now playing song because its response contains current playlist info.
// So this example is daft because we have what we need twice already.
$t = microtime(1);
$curPl = $sp->query(array('method' => 'getPlaylistInfo'));
$t = my_seconds(microtime(1) - $t);
$curPl = $curPl['results'];
$curPl['t'] = $t;



// Getting and using schedule info is a bit more complex. In this example we 
// want the first three scheduled shows today starting this hour. 

// date('G') gets the current 24-hour hour without leading zero. Take care 
// of PHP's conversion of integer strings starting with a 0 as octal.
$startHour = (int) date('G');
$t = microtime(1);
$curShows    = $sp->query(array(
	'method'    => 'getRegularShowsInfo', 
	'When'      => 'today', 
	'StartHour' => $startHour,
));
$t = my_seconds(microtime(1) - $t);
$curShows = $curShows['results'];

// getRegularShowsInfo doesn't sort the results so we do.
function myShowSort($a, $b) {
	global $startHour;
	list($ah, $am, $as) = explode(':', $a['OnairTime']);
	list($bh, $bm, $bs) = explode(':', $b['OnairTime']);
	$ah = ($ah < $startHour ? $ah + 24 : $ah) + $am/60;
	$bh = ($bh < $startHour ? $bh + 24 : $bh) + $bm/60;
	return $ah > $bh ? 1 : -1;
}
usort($curShows, 'myShowSort');
$curShows[0]['t'] = $t;



// That's enough data, now generate an HTML page.

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" 
  "http://www.w3.org/TR/html4/loose.dtd">
<!--[if lte IE 7]> <html class="lte7"> <![endif]-->
<!--[if gt IE 7]>  <html> <![endif]-->
<!--[if !IE]><!--> <html> <!--<![endif]-->
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta http-equiv="Content-Script-Type" content="text/javascript">
  <title>SpinPapi Demo</title>
  <style type="text/css" media="all">
    html, body, div, span, object, iframe, h1, h2, h3, h4, h5, 
    h6, p, blockquote, pre, a, abbr, acronym, address, cite, code, 
    del, dfn, em, img, ins, kbd, q, samp, strong, sub, sup, tt, var, 
    dl, dt, dd, ol, ul, li, fieldset, form, label, legend, table, 
    caption, tbody, tfoot, thead, tr, th, td {
      margin:0;
      padding:0;
      outline:0;
      font-size:1em;
      }
    body {
      color: #000;
      background-color: #f6f2e2;
      font-family: Corbel, "Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", 
        "DejaVu Sans", "Bitstream Vera Sans", "Liberation Sans", Arial, Verdana, 
        "Verdana Ref", sans-serif;
      font-size: 100%;
      padding: 0 2em 4em;
      }
    a {
      color: #a00;
      text-decoration: none;
      }
    a:hover { 
      color: #e00;
      }
    b {
      font-weight: bold;
      color: #393939;
      }
    h1 {
      font-size: 1.25em;
      margin: 1.1em 0 0.1em -1.6em;
      padding: 0.2em 0 0.2em 1.6em;
      text-transform: uppercase;
      max-width: 32em;
      background-color: #ddd;
      }
    html.lte7 h1 {
      width: 32em;
      }
    h2 {
      font-size: 1.16em;
      margin: 1.1em 0 0;
      max-width: 33.2em;
      }
    html.lte7 h2 {
      width: 33.2em;
      }
    p {
      font-size: 1em;
      margin: 0.5em 0 0;
      max-width: 40em;
      text-align: justify;
      }
    html.lte7 p {
      width: 40em;
      }
    ul, ol {
      padding: 0 0 0 2em;
      text-align: justify;
    }
    ul {
      list-style: disc outside none;
      }
    li {
      max-width: 38em;
      font-size: 1em;
      margin: 0.3em 0 0 0;
      }
    li:first-child {
      margin-top: 0.5em;
      }
    hr {
      max-width: 40em;
      margin: 2.5em 0 0;
      }
    html.lte7 hr {
      width: 40em;
      }
  </style>
</head>
<body>

  <h1>SpinPapi example</h1>

  <h2>Now playing (<?php echo $nowSong['t']; ?>)</h2>
  <p><?php
      // NOTE: None of these examples check the SpinPapi result for errors or
      // unusuable data. You should!
      echo 
        "<a href=\"http://spinitron.com/radio/playlist.php"
        . "?station=$myStation&amp;playlist={$nowSong['PlaylistID']}#{$nowSong['SongID']}\">"
        . e($nowSong['ArtistName']) . ' – ' . e($nowSong['SongName'])
        . '</a>';
    ?></p>

  <h2>Recent spins (<?php echo $recentSongs[0]['t']; ?>)</h2>
  <?php
    foreach ($recentSongs as $song) {
      echo '<p>' . e($song['ArtistName']) . ' – ' . e($song['SongName']) . '</p>';
    }
  ?>

  <h2>Current playlist (<?php echo $curPl['t']; ?>)</h2>
  <p><?php
      echo 
        "<a href=\"http://spinitron.com/radio/playlist.php"
        . "?station=$myStation&amp;playlist={$curPl['PlaylistID']}\">"
        . e($curPl['ShowName']) . ' with ' . e($curPl['DJName'])
        . '</a>';
    ?></p>

  <h2>Today on <?php 
      echo strtoupper(e($myStation)) 
	  . ' (' . $curShows[0]['t'] . ")</h2>\n"; 
    for ($i = 0; $i < 3 && isset($curShows[$i]); ++$i) {
      $show =& $curShows[$i];
      echo 
        '<p>' 
        . date('H:ia', strtotime($show['OnairTime']))
        . ' - ' . date('H:ia', strtotime($show['OffairTime']))
        . ": <a href=\"http://spinitron.com/radio/playlist.php"
        . "?station=$myStation&amp;showid={$show['ShowID']}\">"
        . e($show['ShowName']) 
        . '</a></p>';
    }
  ?>

</body>
</html>