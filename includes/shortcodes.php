<?php

/**
 * Register plugin shortcodes
 */
function wst_init() {
	// Register shortcode
	add_shortcode(WST_SHORTCODE_TAG_PEOPLE, 'wst_shortcode_people');
	add_shortcode(WST_SHORTCODE_TAG_PERSON, 'wst_shortcode_person');
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

	$people = swapi_get_people();
	if (!$people) {
		return $output . "<em>Error retrieving people from The StarWars API</em>";
	}

	$title = "People in the StarWars Universe";
	$output .= sprintf("<h2>%s</h2>", $title);

	if (count($people) > 0) {
		$output .= "<ul>";
		foreach ($people as $person) {
			$output .= sprintf('<li>%s (birthyear: %s)</li>', $person->name, $person->birth_year);
		}
		$output .= "</ul>";
	}

	$people_count = count($people);
	$output .= "<em>There are a total of {$people_count} people in the StarWars universe.</em>";

	$output .= "<hr />";

	return $output;
}


/**
 * Parse shortcode for displaying StarWars Person
 *
 * @param array $user_atts Attributes passed in shortcode
 * @param mixed $content Content inside shortcode
 * @param string $tag Shortcode tag (name). Default empty.
 * @return string
 */
function wst_shortcode_person($user_atts = [], $content = null, $tag = '') {

	// change all attribute keys to lowercase
	$user_atts = array_change_key_case((array)$user_atts, CASE_LOWER);

	$default_atts = [
		'id' => null,
	];

	$atts = shortcode_atts($default_atts, $user_atts, WST_SHORTCODE_TAG_PERSON);

	$output = "<hr />";

	if (is_null($atts['id'])) {
		return $output .= "<em>Please specify the ID attribute in shortcode starwars-person.</em>";
	}

	$res = swapi_get_person($atts['id']);
	if (!$res['success']) {
		return $output . "<em>{$res['message']}</em>";
	}

	$title = $res['data']->name;
	$output .= sprintf("<h2>%s</h2>", $title);

	$output .= "<dl>";
	$output .= sprintf('<dt>%s</dt><dd>%s cm</dd>', 'Height', $res['data']->height);
	$output .= sprintf('<dt>%s</dt><dd>%s kg</dd>', 'Mass', $res['data']->mass);
	$output .= sprintf('<dt>%s</dt><dd>%s</dd>', 'Birthyear', $res['data']->birth_year);
	$output .= sprintf('<dt>%s</dt><dd>%s</dd>', 'Films', count($res['data']->films));
	$output .= "</dl>";

	$output .= "<hr />";

	return $output;
}
