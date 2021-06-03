<?php
/**
 * Functions for communicating with The StarWars API
 */

function swapi_get($url) {
	$response = wp_remote_get($url);

	if (wp_remote_retrieve_response_code($response) !== 200) {
		return [
			'success' => false,
			'message' => 'Error in StarWars API request, response code was not 200!'
		];
	}

	$body = wp_remote_retrieve_body($response);
	$data = json_decode($body);

	return [
		'success' => true,
		'data' => $data,
	];
}
