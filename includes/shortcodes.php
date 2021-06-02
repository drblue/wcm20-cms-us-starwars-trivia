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

	$default_atts = [
		'id' => null,
	];

	$atts = shortcode_atts($default_atts, $user_atts, WST_SHORTCODE_TAG_PEOPLE);

	$output = "<hr />";

	$response = wp_remote_get('https://swapi.dev/api/people/');

	if (wp_remote_retrieve_response_code($response) !== 200) {
		return $output . "<em>Error in StarWars API request, response code was not 200!</em>";
	}

	$body = wp_remote_retrieve_body($response);
	$data = json_decode($body);

	// $output .= "<pre>";
	// $output .= print_r($data, true);
	// $output .= "</pre>";
	$output .= "There are {$data->count} people in the StarWars universe.";

	$output .= "<hr />";

	return $output;
}
