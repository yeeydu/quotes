<?php

require_once('.env.php');

// Access the variables
$apiEndpoint = API_ENDPOINT;
$rapidAPIHost = RAPIDAPI_HOST;
$rapidAPIKey = RAPIDAPI_KEY;

// Use the variables in your code

$curl = curl_init();

curl_setopt_array($curl, [
	CURLOPT_URL => $apiEndpoint,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => "",
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 30,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => "GET",
	CURLOPT_HTTPHEADER => [
		"X-RapidAPI-Host: $rapidAPIHost",
		"X-RapidAPI-Key: $rapidAPIKey"
	],
]);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
	echo "cURL Error #:" . $err;
} else {
	$data = json_decode($response);
}
