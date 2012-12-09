<?php
/*//////////////////////////////////////////////////////////////////////////////

	This function illustrates how to form a signed SpinPapi request.
	Deprecated Sep 2011: It is no longer intended for use as a SpinPapi client.
	See: SpinPapiClient.inc.php

	Version date: 2010-11-11

	SpinPapi Client Library
	by Tom Worster, Spinitron is licensed under a Creative Commons 
	Attribution 3.0 United States License.

	THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
	"AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
	LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
	A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
	OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
	SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
	LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
	DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
	THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
	(INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
	OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

//////////////////////////////////////////////////////////////////////////////*/


function spin_papi_query(
	$params,		// (array) query parameters, see api spec
	$userid,		// (str) user id issued by spinitron for use of this api
	$secret,		// (str) secret code issued by spinitron for request signing
	$array = false	// (bool) return results as assoc array rather than obj
)

	/*//////////////////////////////////////////////////////////////////////////////

	spin_papi_query() sends a signed query to the spinitron public api 
	service, receives the response, decodes and returns it.

	CALLING SCRIPT MUST SET DEFAULT TIMEZONE TO STATION'S LOCAL TIMEZONE WITH

		date_default_timezone_set()

	BEFORE CALLING spin_papi_query() FOR THE FIRST TIME

	spin_papi_query() returns:
		(obj) or (array) see api spec for details. 
		(bool) false indicates service failure

	//////////////////////////////////////////////////////////////////////////////*/

{
	// service specs
	$host = 'spinitron.com';
	$url = '/public/spinpapi.php';

	// parameters added to every API query
	$params['papiversion'] = '1'; // SpinPapi version string
	$params['papiuser'] = $userid; // user id
	$params['timestamp'] = gmdate('Y-m-d\TH:i:s\Z'); // timestamp GMT

	// create the query's GET parameter string
	ksort($params);
	$query = array();
	foreach ( $params as $param => $value )
		$query[] = rawurlencode($param) . '=' . rawurlencode($value);
	$query = implode('&', $query);

	// calculate signature
	$signature = rawurlencode(base64_encode(hash_hmac("sha256", 
		"$host\n$url\n$query", $secret, true)));

	// compose request
	$request = "http://$host$url?$query&signature=$signature";

	// do request
	$data = @file_get_contents($request);

	return $data === false ? false : json_decode($data, $array);
}
?>