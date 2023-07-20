<?php

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

require_once('.env.php');

// Access the variables
$apiEndpoint = Random_Quotes_Plugin_API_ENDPOINT;
$rapidAPIHost = RAPIDAPI_HOST;
$rapidAPIKey = RAPIDAPI_KEY;

// $url = $apiEndpoint;
// $args = array([
// 	'method' => 'GET',
// 	'timeout' => 10,
// 	"X-RapidAPI-Key: $rapidAPIKey",
// 	'headers' => array([
// 		"X-RapidAPI-Host: $rapidAPIHost",
// 		"X-RapidAPI-Key: $rapidAPIKey",
// 		'Content-Type' => 'application/json'
// 	]),
// ]);
// $response = wp_remote_get($url, $args);
// $responseBody = wp_remote_retrieve_body($response);
// $result = json_decode($responseBody);
// if (is_array($result) && !is_wp_error($result)) {
	//$data = $response;
	//$data = json_decode($response['body'], true);
// } else {
// 	is_wp_error($result);
// }


// Use the variables in your code

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