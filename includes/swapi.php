<?php
/**
 * Functions for communicating with The StarWars API
 */

/**
 * Get data from a JSON API
 *
 * @param string $url
 * @return boolean|object
 */
function swapi_get_url($url) {
	$response = wp_remote_get($url, [
		'timeout' => 30,
	]);

	if (wp_remote_retrieve_response_code($response) !== 200) {
		return false;
	}

	$body = wp_remote_retrieve_body($response);
	$data = json_decode($body);

	return $data;
}


/**
 * Get (possibly cached) data from a SWAPI endpoint
 *
 * @param string $endpoint
 * @return array
 */
function swapi_get($endpoint) {
	// Get transient for this endpoint
	$items = get_transient("swapi_{$endpoint}");

	// Did (valid) cached data exist for this endpoint?
	if ($items === false) {
		// Cached data didn't exist or had expired for the endpoint
		$items = [];

		// Set URL to fetch
		$url = "https://swapi.dev/api/{$endpoint}";

		// Continue looping until there is no more data to get
		while ($url !== null) {
			// Get data
			$data = swapi_get_url($url);

			// Did we get data?
			if (!$data) {
				return false;
			}

			// Merge retrieved result with previously retrieved result
			$items = array_merge($items, $data->results);

			// Do we have more results to retrieve?
			$url = $data->next;
		}

		// Store retrieved data in the cache for 4 hours
		set_transient("swapi_{$endpoint}", $items, 4 * HOUR_IN_SECONDS);
	}

	return $items;
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
