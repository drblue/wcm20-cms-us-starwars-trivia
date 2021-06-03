<?php

class StarWarsTriviaFilmsWidget extends WP_Widget {
	/**
	 * Construct a new widget instance.
	 */
	public function __construct() {
		parent::__construct(
			'wcm20-starwars-trivia-films-widget', // Base ID
			'StarWars Trivia: Films', // Name
			[
				'description' => 'Widget for displaying trivia about StarWars films.',
			]
		);
	}

	/**
	 * Front-end display of the widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args Widget arguments.
	 * @param array $instance Saved option values for this specific instance of the widget.
	 * @return void
	 */
	public function widget($args, $instance) {
		// start widget
		echo $args['before_widget'];

		// render title
		if (!empty($instance['title'])) {
			echo $args['before_title'] . $instance['title'] . $args['after_title'];
		}

		$res = swapi_get_films();
		if (!$res['success']) {
			echo "<p><em>{$res['message']}</em></p>";
			return;
		}

		echo "<ul>";
		foreach ($res['data']->results as $film) {
			?>
				<li>
					<h5><?php echo $film->title; ?></h5>

					<hr />

					<dl>
						<dt>Episode</dt>
						<dd><?php echo $film->episode_id; ?></dd>
					</dl>
					<dl>
						<dt>Release Date</dt>
						<dd><?php echo $film->release_date; ?></dd>
					</dl>
				</li>
			<?php
		}
		echo "</ul>";

		// end widget
		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Current saved values for this instance of the widget.
	 * @return void
	 */
	public function form($instance) {
		// do we have a title set? if so, use it, otherwise set empty title
		$title = isset($instance['title'])
			? $instance['title']
			: __('StarWars Film Trivia', 'wst');

		?>
			<!-- title -->
			<p>
				<label for="<?php echo $this->get_field_id('title') ?>">Title:</label>

				<input
					class="widefat"
					id="<?php echo $this->get_field_id('title') ?>"
					name="<?php echo $this->get_field_name('title') ?>"
					type="text"
					value="<?php echo $title; ?>"
				>
			</p>
		<?php
	}

	/**
	 * Sanitize widget form data before they are saved to the database.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Form values just sent to be saved.
	 * @param array $old_instance Currently saved values.
	 * @return void
	 */
	public function update($new_instance, $old_instance) {
		$instance = [];

		$instance['title'] = (!empty($new_instance['title']))
			? strip_tags($new_instance['title'])
			: '';

		return $instance;
	}
}
