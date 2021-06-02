<?php

/**
 * Register plugin shortcodes
 */
function wst_init() {
	// Register shortcode
	add_shortcode(WST_SHORTCODE_TAG_PEOPLE, 'wst_shortcode_people');
}
add_action('init', 'wst_init');

/**
 * Parse shortcode for displaying StarWars People
 *
 * @param array $user_atts Attributes passed in shortcode
 * @param mixed $content Content inside shortcode
 * @param string $tag Shortcode tag (name). Default empty.
 * @return string
 */
function wst_shortcode_people($user_atts = [], $content = null, $tag = '') {

	// change all attribute keys to lowercase
	$user_atts = array_change_key_case((array)$user_atts, CASE_LOWER);

	$default_atts = [];

	$atts = shortcode_atts($default_atts, $user_atts, WST_SHORTCODE_TAG_PEOPLE);

	$output = "<hr />";

	$response = wp_remote_get('https://swapi.dev/api/people/');

	if (wp_remote_retrieve_response_code($response) !== 200) {
		return $output . "<em>Error in StarWars API request, response code was not 200!</em>";
	}

	$body = wp_remote_retrieve_body($response);
	$data = json_decode($body);

	$title = "People in the StarWars Universe";
	$output .= sprintf("<h2>%s</h2>", $title);

	if (count($data->results) > 0) {
		$output .= "<ul>";
		foreach ($data->results as $person) {
			$output .= sprintf('<li>%s (birthyear: %s)</li>', $person->name, $person->birth_year);
		}
		$output .= "</ul>";
	}

	$output .= "<em>There are a total of {$data->count} people in the StarWars universe.</em>";

	$output .= "<hr />";

	return $output;
}
