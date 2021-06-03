<?php
/**
 * Functions for communicating with The StarWars API
 */

/**
 * Get data from a SWAPI endpoint
 *
 * @param string $endpoint
 * @return array
 */
function swapi_get($endpoint) {
	$response = wp_remote_get("https://swapi.dev/api/{$endpoint}");

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

/**
 * Get films
 *
 * @return array
 */
function swapi_get_films() {
	return swapi_get("films/");
}

/**
 * Get people
 *
 * @return array
 */
function swapi_get_people() {
	return swapi_get("people/");
}

/**
 * Get person
 *
 * @param int $id ID of person to get
 * @return array
 */
function swapi_get_person($id) {
	return swapi_get("people/{$id}");
}
