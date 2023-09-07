<?php

if (!defined('ABSPATH')) {
  die('Invalid request.');
}

$apiEndpoint = Random_Quotes_Plugin_API_ENDPOINT;
$rapidAPIHost = RAPIDAPI_HOST;
$rapidAPIKey = RAPIDAPI_KEY;

//  $url = $apiEndpoint;
//  $args = array([
// 	'method'   => 'GET',
// 	'timeout'  => 45,
// 	'blocking' => true, 
// 	'sslverify' => false,
// 	'httpversion' => '1.0',
// 	'redirection' => 5,
// 	'headers' => array(
// 		'Accept'=> 'application/json',
// 		'Content-Type' =>'application/json',
// 		'X-RapidAPI-Key' => $rapidAPIKey,
// 	),
// 	   $response => json_encode('body'),
//  ]);
// $response = wp_remote_get($url, $args);
// $responseBody = wp_remote_retrieve_body($response);
// $result = json_decode($responseBody);
// if (is_array($result) && !is_wp_error($result)) {
// 	//$data = $responseBody;
// 	$data = json_decode($result['body'], true);
// } else {
// 	error_log('Error');
// }
// print_r($data);

// Access the variables 

/*
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiEndpoint );
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($ch, CURLOPT_HTTPHEADER , [
	"X-RapidAPI-Host: $rapidAPIHost",
	"X-RapidAPI-Key: $rapidAPIKey"
]);
$response = curl_exec($ch);
if (curl_errno($ch)) {
	echo esc_html('Error:') . curl_error($ch);
}else {
	$data = json_decode($response);
}

curl_close($ch);
*/
/*
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => $apiEndpoint,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "X-RapidAPI-Host: $rapidAPIHost",
	"X-RapidAPI-Key: $rapidAPIKey"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
	$data = json_decode($response);
}
*/

 
function getApi()
{
  $apiEndpoint = Random_Quotes_Plugin_API_ENDPOINT;
  $rapidAPIHost = RAPIDAPI_HOST;
  $rapidAPIKey = RAPIDAPI_KEY;

  $url = $apiEndpoint;
  $arguments = array(
    'method' => 'GET',
    'headers' => array(
      //'Accept' => 'application/json',
      'Content-Type' => 'application/json',
      'X-RapidAPI-Host' => $rapidAPIHost,
      'X-RapidAPI-Key' => $rapidAPIKey,
    ),
  );

  $response = wp_remote_get($url, $arguments);

  if (is_wp_error($response)) {
    $error_message = $response->get_error_message();
    echo "Something went wrong: $error_message";
  }
  $data = json_decode($response);
}

 