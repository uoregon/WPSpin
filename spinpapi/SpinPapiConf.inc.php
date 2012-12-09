<?php

/*

CONFIGURATIONS for example.php and spquery. 

This file is named SpinPapiConfig.template.inc.php. Make a copy of this file 
called SpinPapiConfig.inc.php. Then, in the copy, review and adjust the all 
of following. Then install that copy in your PHP include path or put in the
same directory as example.php and/or spquery.

*/

// Set your station's timezone
// See: http://www.php.net/manual/en/timezones.america.php
date_default_timezone_set('America/Los_Angeles');

// EITHER put SpinPapiClient.inc.php somewhere in your PHP include path 
// OR uncomment and change the following to point to it:
$mySpClientPath = dirname(__FILE__) . '/SpinPapiClient.inc.php';

// Change these three
$mySpUserID = '';
$mySpSecret = '';
$myStation  = '';

// To use a file cache, uncomment and set to the cache directory's path
$myFCache = dirname(__FILE__) . '/tmp';

// To use Memcached, uncomment and set your memcache servers
// See: http://www.php.net/manual/en/memcached.addservers.php
//$myMemcache = array(array('localhost', 11211));

// You MUST use data caching. Either use one of the caches in SpinPapiClient
// or implement your own. This is a condition for use of SpinPapi. 


?>
