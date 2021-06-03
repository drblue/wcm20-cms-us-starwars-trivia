<?php
/**
 * Functions for communicating with The StarWars API
 */

/**
 * Get data from a JSON API
 *
 * @param string $url
 * @return array
 */
function swapi_get_url($url) {
	$response = wp_remote_get($url, [
		'timeout' => 30,
	]);

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
 * Get (possibly cached) data from a SWAPI endpoint
 *
 * @param string $endpoint
 * @return array
 */
function swapi_get($endpoint) {
	// Get transient for this endpoint
	$res = get_transient("swapi_{$endpoint}");

	// Did (valid) cached data exist for this endpoint?
	if ($res === false) {
		// Cached data didn't exist or had expired for the endpoint
		$res = swapi_get_url("https://swapi.dev/api/{$endpoint}");

		// Fake a slow api 😈
		// sleep(5);

		// Store retrieved data in the cache for 4 hours
		set_transient("swapi_{$endpoint}", $res, 4 * HOUR_IN_SECONDS);
	}

	return $res;
}

/**
 * Get films
 *
 * @return array
 */
function swapi_get_films() {
	return swapi_get("films");
}

/**
 * Get people
 *
 * @return array
 */
function swapi_get_people() {
	return swapi_get("people");
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
